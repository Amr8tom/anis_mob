import 'dart:async';
import 'dart:convert';
import 'dart:typed_data';

import 'package:anis/core/dio/dio_helper.dart';
import 'package:anis/core/error/failure.dart';
import 'package:anis/core/local_storage/local_storage.dart';
import 'package:anis/core/local_storage/storage_keys.dart';
import 'package:dio/dio.dart';
import 'package:flutter_test/flutter_test.dart';

/// Minimal in-memory LocalStorage exposing only the keys DioHelper reads.
class _FakeStorage implements LocalStorage {
  _FakeStorage({this.token, this.lang});

  final String? token;
  final String? lang;

  @override
  String? getString({required String key}) {
    if (key == StorageKeys.token.name) return token;
    if (key == StorageKeys.lang.name) return lang;
    return null;
  }

  @override
  dynamic noSuchMethod(Invocation invocation) => null;
}

/// Canned HTTP adapter: returns a fixed status/body, or throws a DioException,
/// and captures the outgoing RequestOptions for header assertions.
class _FakeAdapter implements HttpClientAdapter {
  _FakeAdapter({
    this.statusCode = 200,
    this.body,
    this.contentType = 'application/json',
    this.throwException,
  });

  final int statusCode;
  final Object? body;
  final String contentType;
  final DioException? throwException;

  RequestOptions? lastRequest;

  @override
  void close({bool force = false}) {}

  @override
  Future<ResponseBody> fetch(
    RequestOptions options,
    Stream<Uint8List>? requestStream,
    Future<void>? cancelFuture,
  ) async {
    lastRequest = options;
    if (throwException != null) throw throwException!;

    final payload = body is String ? body as String : jsonEncode(body);
    return ResponseBody.fromString(
      payload,
      statusCode,
      headers: {
        Headers.contentTypeHeader: [contentType],
      },
    );
  }
}

DioHelper _helperWith(_FakeAdapter adapter, {String? token, String? lang}) {
  final dio = Dio();
  dio.httpClientAdapter = adapter;
  return DioHelper(_FakeStorage(token: token, lang: lang), dio: dio);
}

void main() {
  group('DioHelper success codes', () {
    test('200 returns decoded body', () async {
      final helper = _helperWith(
        _FakeAdapter(statusCode: 200, body: {'success': true, 'data': {'x': 1}}),
      );
      final result = await helper.get(url: '/', requiresAuth: false);
      expect(result, isA<Map>());
      expect((result as Map)['success'], true);
    });

    test('201 returns decoded body', () async {
      final helper = _helperWith(
        _FakeAdapter(statusCode: 201, body: {'success': true}),
      );
      final result = await helper.post(url: '/', requiresAuth: false);
      expect((result as Map)['success'], true);
    });

    test('204 completes without throwing', () async {
      final helper = _helperWith(_FakeAdapter(statusCode: 204, body: ''));
      await expectLater(
        helper.post(url: '/', requiresAuth: false),
        completes,
      );
    });
  });

  group('DioHelper headers', () {
    test('public request does not include Authorization', () async {
      final adapter = _FakeAdapter(statusCode: 200, body: {'ok': true});
      final helper = _helperWith(adapter, token: 'secret-token');

      await helper.get(url: '/', requiresAuth: false);

      expect(adapter.lastRequest!.headers.containsKey('Authorization'), isFalse);
      expect(adapter.lastRequest!.headers['Accept'], 'application/json');
    });

    test('authenticated request includes Authorization when token exists', () async {
      final adapter = _FakeAdapter(statusCode: 200, body: {'ok': true});
      final helper = _helperWith(adapter, token: 'secret-token');

      await helper.get(url: '/', requiresAuth: true);

      expect(adapter.lastRequest!.headers['Authorization'], 'Bearer secret-token');
    });

    test('authenticated request omits Authorization when token is empty', () async {
      final adapter = _FakeAdapter(statusCode: 200, body: {'ok': true});
      final helper = _helperWith(adapter, token: null);

      await helper.get(url: '/', requiresAuth: true);

      expect(adapter.lastRequest!.headers.containsKey('Authorization'), isFalse);
    });
  });

  group('DioHelper error mapping', () {
    Future<void> expectFailure(int status, Object? body, Matcher matcher) async {
      final helper = _helperWith(_FakeAdapter(statusCode: status, body: body));
      await expectLater(
        helper.post(url: '/', requiresAuth: false),
        throwsA(matcher),
      );
    }

    test('401 -> UnauthorizedFailure', () =>
        expectFailure(401, {'message': 'no'}, isA<UnauthorizedFailure>()));

    test('402 -> InsufficientBalanceFailure', () =>
        expectFailure(402, {'message': 'no balance'}, isA<InsufficientBalanceFailure>()));

    test('409 -> ConflictFailure', () =>
        expectFailure(409, {'message': 'conflict'}, isA<ConflictFailure>()));

    test('422 -> ValidationFailure with flattened errors', () async {
      final helper = _helperWith(_FakeAdapter(statusCode: 422, body: {
        'message': 'Invalid',
        'errors': {
          'email': ['Required'],
          'password': ['Too short', 'Weak'],
        },
      }));

      try {
        await helper.post(url: '/', requiresAuth: false);
        fail('Expected ValidationFailure');
      } on ValidationFailure catch (failure) {
        expect(failure.errors.length, 3);
        expect(failure.message, 'Invalid');
      }
    });

    test('429 -> RateLimitFailure', () =>
        expectFailure(429, {'message': 'slow down'}, isA<RateLimitFailure>()));

    test('500 -> ServerFailure', () =>
        expectFailure(500, {'message': 'boom'}, isA<ServerFailure>()));

    test('malformed non-JSON error body still maps to ServerFailure', () async {
      final helper = _helperWith(
        _FakeAdapter(statusCode: 500, body: 'Server Down', contentType: 'text/plain'),
      );
      await expectLater(
        helper.get(url: '/', requiresAuth: false),
        throwsA(isA<ServerFailure>()),
      );
    });

    test('connection timeout -> NetworkFailure', () async {
      final helper = _helperWith(_FakeAdapter(
        throwException: DioException(
          requestOptions: RequestOptions(path: '/'),
          type: DioExceptionType.connectionTimeout,
        ),
      ));
      await expectLater(
        helper.get(url: '/', requiresAuth: false),
        throwsA(isA<NetworkFailure>()),
      );
    });
  });
}

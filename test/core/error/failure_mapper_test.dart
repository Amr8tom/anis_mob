import 'package:anis/core/error/failure.dart';
import 'package:anis/core/error/failure_mapper.dart';
import 'package:dio/dio.dart';
import 'package:flutter_test/flutter_test.dart';

void main() {
  group('FailureMapper.fromResponse', () {
    test('401 -> UnauthorizedFailure with backend message', () {
      final failure = FailureMapper.fromResponse(401, {'message': 'Unauthenticated.'});
      expect(failure, isA<UnauthorizedFailure>());
      expect(failure.message, 'Unauthenticated.');
    });

    test('402 -> InsufficientBalanceFailure', () {
      expect(
        FailureMapper.fromResponse(402, {'message': 'No balance'}),
        isA<InsufficientBalanceFailure>(),
      );
    });

    test('403 -> ForbiddenFailure', () {
      expect(FailureMapper.fromResponse(403, null), isA<ForbiddenFailure>());
    });

    test('404 -> NotFoundFailure', () {
      expect(FailureMapper.fromResponse(404, null), isA<NotFoundFailure>());
    });

    test('409 -> ConflictFailure preserving message', () {
      final failure = FailureMapper.fromResponse(409, {'message': 'Already joined'});
      expect(failure, isA<ConflictFailure>());
      expect(failure.message, 'Already joined');
    });

    test('422 -> ValidationFailure flattens Laravel errors and reads message', () {
      final failure = FailureMapper.fromResponse(422, {
        'message': 'The given data was invalid.',
        'errors': {
          'phone_number': ['The phone number has already been taken.'],
          'password': ['The password must be at least 6 characters.', 'Weak.'],
        },
      });

      expect(failure, isA<ValidationFailure>());
      final validation = failure as ValidationFailure;
      expect(validation.message, 'The given data was invalid.');
      expect(validation.errors.length, 3);
      expect(validation.errors, contains('Weak.'));
    });

    test('429 -> RateLimitFailure', () {
      expect(FailureMapper.fromResponse(429, null), isA<RateLimitFailure>());
    });

    test('500 -> ServerFailure', () {
      expect(FailureMapper.fromResponse(500, {'message': 'Boom'}), isA<ServerFailure>());
    });

    test('malformed non-JSON body does not crash', () {
      final failure = FailureMapper.fromResponse(500, '<html>Server Error</html>');
      expect(failure, isA<ServerFailure>());
      expect(failure.message, isNotNull);
    });
  });

  group('FailureMapper.fromDioException', () {
    DioException dioOf(DioExceptionType type) =>
        DioException(requestOptions: RequestOptions(path: '/'), type: type);

    test('connection timeout -> NetworkFailure', () {
      expect(
        FailureMapper.fromDioException(dioOf(DioExceptionType.connectionTimeout)),
        isA<NetworkFailure>(),
      );
    });

    test('connection error -> NetworkFailure', () {
      expect(
        FailureMapper.fromDioException(dioOf(DioExceptionType.connectionError)),
        isA<NetworkFailure>(),
      );
    });

    test('bad response with payload maps by status', () {
      final exception = DioException(
        requestOptions: RequestOptions(path: '/'),
        type: DioExceptionType.badResponse,
        response: Response(
          requestOptions: RequestOptions(path: '/'),
          statusCode: 409,
          data: {'message': 'Conflict!'},
        ),
      );
      expect(FailureMapper.fromDioException(exception), isA<ConflictFailure>());
    });
  });
}

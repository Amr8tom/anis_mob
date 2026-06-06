import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:pretty_dio_logger/pretty_dio_logger.dart';

import '../error/failure_mapper.dart';
import '../local_storage/local_storage.dart';
import '../local_storage/storage_keys.dart';

/// Thin, feature-agnostic HTTP client. Every verb funnels through a single
/// internal request method that builds headers, handles 200/201/204, and
/// converts any error (HTTP status or transport) into a typed [Failure].
///
/// Callers receive the decoded response body (`Map`, `List`, `String`, or
/// `null` for 204). On failure a [Failure] is thrown — never a `null` return,
/// never a raw `DioException`.
class DioHelper {
  final LocalStorage storage;
  final Dio dio;

  DioHelper(this.storage, {Dio? dio}) : dio = dio ?? Dio() {
    // Logger stays in debug only and never prints headers or bodies, so
    // Authorization tokens, passwords, and response tokens are not leaked.
    this.dio.interceptors.add(
          PrettyDioLogger(
            requestHeader: false,
            requestBody: false,
            responseBody: false,
            responseHeader: false,
            request: true,
            error: true,
            compact: true,
            maxWidth: 90,
            enabled: kDebugMode,
          ),
        );
  }

  String get _currentLanguage {
    final language = storage.getString(key: StorageKeys.lang.name);
    return language == null || language.isEmpty ? 'en' : language;
  }

  String get _token => storage.getString(key: StorageKeys.token.name) ?? '';

  // ───────────────────────────── Public API ─────────────────────────────

  Future<dynamic> get({
    required String url,
    Map<String, dynamic>? queryParameters,
    bool requiresAuth = true,
  }) {
    return _request(
      method: 'GET',
      url: url,
      queryParameters: queryParameters,
      requiresAuth: requiresAuth,
    );
  }

  Future<dynamic> post({
    required String url,
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool requiresAuth = true,
  }) {
    return _request(
      method: 'POST',
      url: url,
      data: data,
      queryParameters: queryParameters,
      requiresAuth: requiresAuth,
    );
  }

  Future<dynamic> put({
    required String url,
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool requiresAuth = true,
  }) {
    return _request(
      method: 'PUT',
      url: url,
      data: data,
      queryParameters: queryParameters,
      requiresAuth: requiresAuth,
    );
  }

  Future<dynamic> patch({
    required String url,
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool requiresAuth = true,
  }) {
    return _request(
      method: 'PATCH',
      url: url,
      data: data,
      queryParameters: queryParameters,
      requiresAuth: requiresAuth,
    );
  }

  Future<dynamic> delete({
    required String url,
    dynamic data,
    Map<String, dynamic>? queryParameters,
    bool requiresAuth = true,
  }) {
    return _request(
      method: 'DELETE',
      url: url,
      data: data,
      queryParameters: queryParameters,
      requiresAuth: requiresAuth,
    );
  }

  // ─────────────────────────── Internals ───────────────────────────

  Future<dynamic> _request({
    required String method,
    required String url,
    dynamic data,
    Map<String, dynamic>? queryParameters,
    required bool requiresAuth,
  }) async {
    try {
      final response = await dio.request(
        url,
        data: data,
        queryParameters: queryParameters,
        options: Options(
          method: method,
          headers: _buildHeaders(requiresAuth: requiresAuth),
          // Handle status codes ourselves instead of letting Dio throw.
          validateStatus: (_) => true,
        ),
      );

      final code = response.statusCode ?? 0;
      if (code == 200 || code == 201 || code == 204) {
        return response.data;
      }

      throw FailureMapper.fromResponse(code, response.data);
    } on DioException catch (exception) {
      // Connection errors / timeouts never reach the status check above.
      throw FailureMapper.fromDioException(exception);
    }
  }

  /// Builds request headers. Authorization is only attached for authenticated
  /// requests when a non-empty token exists — public requests never send an
  /// empty Bearer header.
  Map<String, String> _buildHeaders({required bool requiresAuth}) {
    final headers = <String, String>{
      'Accept': 'application/json',
      'App-Language': _currentLanguage,
    };

    if (requiresAuth) {
      final token = _token;
      if (token.isNotEmpty) {
        headers['Authorization'] = 'Bearer $token';
      }
    }

    return headers;
  }
}

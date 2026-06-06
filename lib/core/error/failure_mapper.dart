import 'package:dio/dio.dart';

import 'failure.dart';

/// Single source of truth for converting HTTP responses and `DioException`s
/// into typed [Failure]s. Keeps all status-code handling in one place so no
/// duplicated logic lives in [DioHelper] or repositories.
abstract final class FailureMapper {
  /// Maps a completed (non-2xx) HTTP response to a [Failure].
  static Failure fromResponse(int? statusCode, dynamic data) {
    final message = _messageFrom(data);

    switch (statusCode) {
      case 401:
        return UnauthorizedFailure(
          message: message ?? const UnauthorizedFailure().message!,
        );
      case 402:
        return InsufficientBalanceFailure(
          message: message ?? const InsufficientBalanceFailure().message!,
        );
      case 403:
        return ForbiddenFailure(
          message: message ?? const ForbiddenFailure().message!,
        );
      case 404:
        return NotFoundFailure(
          message: message ?? const NotFoundFailure().message!,
        );
      case 409:
        return ConflictFailure(
          message: message ?? const ConflictFailure().message!,
        );
      case 422:
        return data is Map<String, dynamic>
            ? ValidationFailure.fromMap(data)
            : ValidationFailure(
                message: message ?? 'The given data was invalid.',
              );
      case 429:
        return RateLimitFailure(
          message: message ?? const RateLimitFailure().message!,
        );
      case 500:
      case 502:
      case 503:
        return ServerFailure(message: message ?? 'Server error');
      default:
        if (statusCode != null && statusCode >= 500) {
          return ServerFailure(message: message ?? 'Server error');
        }
        return ServerFailure(message: message ?? 'Unexpected error ($statusCode)');
    }
  }

  /// Maps a `DioException` (connection issues, timeouts, or an error response).
  static Failure fromDioException(DioException exception) {
    switch (exception.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.receiveTimeout:
      case DioExceptionType.connectionError:
        return const NetworkFailure();
      case DioExceptionType.cancel:
        return const UnknownFailure(message: 'Request cancelled');
      case DioExceptionType.badCertificate:
        return const NetworkFailure(message: 'Bad SSL certificate');
      case DioExceptionType.badResponse:
      case DioExceptionType.unknown:
        final response = exception.response;
        if (response != null) {
          return fromResponse(response.statusCode, response.data);
        }
        // A SocketException surfaces here as an "unknown" Dio error.
        final error = exception.error;
        if (error.toString().contains('SocketException')) {
          return const NetworkFailure();
        }
        return const UnknownFailure();
    }
  }

  /// Safely extracts the backend `message` from a (possibly malformed) body.
  static String? _messageFrom(dynamic data) {
    if (data is Map) {
      final message = data['message'];
      if (message is String && message.trim().isNotEmpty) {
        return message;
      }
    }
    if (data is String && data.trim().isNotEmpty && data.length <= 300) {
      return data;
    }
    return null;
  }
}

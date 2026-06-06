import 'package:equatable/equatable.dart';
import 'package:flutter/cupertino.dart';

/// Base type for every recoverable error surfaced to the domain/presentation
/// layers. All networking errors are converted to a [Failure] subtype by
/// [FailureMapper] — a raw [Exception]/`DioException` must never escape the
/// data layer.
abstract class Failure extends Equatable {
  final String? message;

  const Failure({this.message});

  @override
  List<Object?> get props => [message];
}

/// No connectivity / timeout / socket error.
class NetworkFailure extends Failure {
  const NetworkFailure({super.message = 'No internet connection'});

  @override
  List<Object?> get props => [message];
}

/// 401 — missing/expired/invalid credentials.
class UnauthorizedFailure extends Failure {
  const UnauthorizedFailure({super.message = 'Unauthorized'});

  @override
  List<Object?> get props => [message];
}

/// 403 — authenticated but not allowed.
class ForbiddenFailure extends Failure {
  const ForbiddenFailure({super.message = 'Forbidden'});

  @override
  List<Object?> get props => [message];
}

/// 404 — resource not found.
class NotFoundFailure extends Failure {
  const NotFoundFailure({super.message = 'Not found'});

  @override
  List<Object?> get props => [message];
}

/// 402 — no active subscription / insufficient balance.
class InsufficientBalanceFailure extends Failure {
  const InsufficientBalanceFailure({
    super.message = 'Insufficient subscription balance',
  });

  @override
  List<Object?> get props => [message];
}

/// 409 — business conflict (already joined, session full, already checked in…).
class ConflictFailure extends Failure {
  const ConflictFailure({super.message = 'Conflict'});

  @override
  List<Object?> get props => [message];
}

/// 422 — Laravel validation error. [errors] is the flattened list of messages.
class ValidationFailure extends Failure {
  final List<String> errors;

  const ValidationFailure({
    required super.message,
    this.errors = const [],
  });

  /// Builds from a Laravel error envelope:
  /// `{ "message": "...", "errors": { "field": ["msg", ...] } }`.
  factory ValidationFailure.fromMap(Map<String, dynamic> map) {
    final rawErrors = map['errors'];
    final flattened = <String>[];

    if (rawErrors is Map) {
      for (final value in rawErrors.values) {
        if (value is List) {
          flattened.addAll(value.map((e) => e.toString()));
        } else if (value != null) {
          flattened.add(value.toString());
        }
      }
    }

    final message = map['message'];
    return ValidationFailure(
      message: message is String && message.trim().isNotEmpty
          ? message
          : 'The given data was invalid.',
      errors: flattened,
    );
  }

  @override
  List<Object?> get props => [message, errors];
}

/// 429 — rate limited.
class RateLimitFailure extends Failure {
  const RateLimitFailure({
    super.message = 'Too many requests. Please slow down.',
  });

  @override
  List<Object?> get props => [message];
}

/// 5xx — server-side error.
class ServerFailure extends Failure {
  final List<String>? errors;

  const ServerFailure({
    required super.message,
    this.errors,
  });

  factory ServerFailure.fromString(String message) {
    return ServerFailure(message: message);
  }

  factory ServerFailure.fromMap(Map<String, dynamic> map) {
    return ServerFailure(
      message: map['message']?.toString() ?? 'Server failure',
    );
  }

  @override
  List<Object?> get props => [message, errors];
}

/// Local cache / offline fallback failure.
class CacheFailure extends Failure {
  const CacheFailure({super.message = 'Cache failure'});

  @override
  List<Object?> get props => [message];
}

/// Anything we could not classify.
class UnknownFailure extends Failure {
  const UnknownFailure({super.message = 'Something went wrong'});

  @override
  List<Object?> get props => [message];
}

class InvalidOtpFailure extends Failure {
  const InvalidOtpFailure(String message) : super(message: message);

  @override
  List<Object?> get props => [message];
}

class SocialLoginFailure extends Failure {
  final dynamic exception;

  const SocialLoginFailure(this.exception);

  @override
  List<Object?> get props => [exception];
}

String getFailureMessage(Failure failure, BuildContext context) {
  if (failure is ValidationFailure) {
    if (failure.errors.isNotEmpty) {
      return '${failure.message}\n${failure.errors.join('\n')}';
    }
    return failure.message ?? 'Validation failure';
  }
  if (failure is ServerFailure) {
    final message = failure.message;
    if (failure.errors != null && failure.errors!.isNotEmpty) {
      return '$message\n${failure.errors!.join('\n')}';
    }
    return failure.message ?? 'Server failure';
  }
  if (failure is InvalidOtpFailure) {
    return failure.message ?? 'Invalid OTP';
  }
  // All other typed failures carry a human-readable backend/default message.
  return failure.message ?? 'Something went wrong';
}

import 'package:equatable/equatable.dart';
import 'package:flutter/cupertino.dart';

abstract class Failure extends Equatable {
  final String? message;

  const Failure({this.message});

  @override
  List<Object?> get props => [message];
}

class ServerFailure extends Failure {
  final List<String>? errors;

  const ServerFailure({
    required super.message,
    this.errors,
  });

  /// factory method to take  errors masssage from json method
  factory ServerFailure.fromString(String message) {
    return ServerFailure(
      message: message,
    );
  }

  factory ServerFailure.fromMap(Map<String, dynamic> map) {
    return ServerFailure(
      message: map['message'] ?? 'Server Failure',
    );
  }
}

class ValidationFailure extends Failure {
  final List<String>? errors;

  const ValidationFailure({
    required super.message,
    this.errors,
  });

  /// method to take  errors masssage from json method
  factory ValidationFailure.fromMap(Map<String, dynamic> map) {
    if (map['errors'] != null) {
      final errorsMap = Map<String, dynamic>.from(map['errors']);
      final errorsList =
          errorsMap.values.expand((error) => List<String>.from(error)).toList();
      return ValidationFailure(
        message: map['title'] ?? 'Validation Error',
        errors: errorsList,
      );
    } else {
      return ValidationFailure(
        message: map['title'] ?? 'Validation Error',
        errors: [],
      );
    }
  }

  @override
  List<Object?> get props => [message, errors];
}

class UnauthorizedFailure extends Failure {
  const UnauthorizedFailure();

  @override
  List<Object?> get props => [];
}

class CacheFailure extends Failure {
  const CacheFailure();

  @override
  List<Object?> get props => [];
}

class UnknownFailure extends Failure {
  const UnknownFailure();

  @override
  List<Object?> get props => [];
}

class InvalidOtpFailure extends Failure {
  const InvalidOtpFailure(String message) : super(message: message);

  @override
  List<Object?> get props => [];
}

class SocialLoginFailure extends Failure {
  final dynamic exception;

  const SocialLoginFailure(
    this.exception,
  );

  @override
  List<Object?> get props => [];
}

String getFailureMessage(Failure failure, BuildContext context) {
  if (failure is ServerFailure) {
    final message = failure.message;
    if (failure.errors != null && failure.errors!.isNotEmpty) {
      return '$message\n${failure.errors!.join('\n')}';
    }
    return failure.message ?? 'Server Failure';
  } else if (failure is InvalidOtpFailure) {
    return failure.message ?? 'Invalid OTP';
  } else if (failure is UnauthorizedFailure) {
    return 'Unauthorized';
  } else if (failure is CacheFailure) {
    return 'Cache Failure';
  } else if (failure is ValidationFailure) {
    return failure.message ?? 'Validation Failure';
  } else if (failure is UnknownFailure) {
    return 'Unknown Failure';
  } else {
    return 'Unknown Failure';
  }
}

// {
// "success": true,
// "message": "User created successfully",
// "error": null
// }

import 'package:equatable/equatable.dart';

class CreateUserInfo extends Equatable {
  final bool success;
  final String? message;
  final String? error;
  final String? token;
  const CreateUserInfo({
    required this.success,
    this.message,
    this.error,
    this.token,
  });
  @override
  List<Object?> get props => [success, message, error, token];
}

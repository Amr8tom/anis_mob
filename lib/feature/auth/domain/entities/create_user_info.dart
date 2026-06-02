// {
// "success": true,
// "message": "User created successfully",
// "error": null
// }

import 'package:equatable/equatable.dart';

class CreateUserInfo extends Equatable{
  final bool success;
  final String? message;
  final String? error;
  const CreateUserInfo({
    required this.success,
    this.message,
    this.error,
  });
  @override
  List<Object?> get props => [success, message, error];
}
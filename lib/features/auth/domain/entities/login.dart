import 'package:equatable/equatable.dart';

class Login extends Equatable {
  final String? token;
  final String? fullName;
  final String? role;
  final bool success;
  final bool profileCompleted;

  const Login({
    required this.token,
    required this.fullName,
    required this.role,
    required this.success,
    required this.profileCompleted,
  });

  @override
  List<Object?> get props => [token, fullName, role, success, profileCompleted];
}

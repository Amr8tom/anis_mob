import 'package:equatable/equatable.dart';

class Login extends Equatable {
  final String? accessToken;
  final String? userName;
  final String? role;
  final bool success;

  const Login({
    required this.accessToken,
    required this.userName,
    required this.role,
    required this.success,
  });

  @override
  List<Object?> get props => [accessToken, userName, role, success];
}

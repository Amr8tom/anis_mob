import '../../domain/entities/login.dart';

class LoginModel extends Login {
  const LoginModel({
    required super.token,
    required super.fullName,
    required super.role,
    required super.success,
  });

  factory LoginModel.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>?;
    final user = data?['user'] as Map<String, dynamic>?;
    return LoginModel(
      token: data?['token'] as String?,
      fullName: user?['full_name'] as String?,
      role: user?['role'] as String?,
      success: json['success'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'token': token,
        'fullName': fullName,
        'role': role,
        'success': success,
      };
}

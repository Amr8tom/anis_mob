import '../../domain/entities/login.dart';

class LoginModel extends Login {
  const LoginModel({
    required super.accessToken,
    required super.userName,
    required super.role,
    required super.success,
  });

  factory LoginModel.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>?;
    return LoginModel(
      accessToken: data?['accessToken'] as String?,
      userName: data?['userName'] as String?,
      role: data?['role'] as String?,
      success: json['success'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
    'accessToken': accessToken,
    'userName': userName,
    'role': role,
    'success': success,
  };
}

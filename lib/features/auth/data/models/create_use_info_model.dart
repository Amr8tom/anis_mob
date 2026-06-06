import '../../domain/entities/create_user_info.dart';

class CreateUseInfoModel extends CreateUserInfo {
  const CreateUseInfoModel({
    required super.success,
    required super.message,
    required super.error,
    required super.token,
  });

  factory CreateUseInfoModel.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>?;
    return CreateUseInfoModel(
      success: json['success'] as bool? ?? false,
      message: json['message'] as String?,
      error: json['error'] as String?,
      token: data?['token'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'success': success,
        'message': message,
        'error': error,
        'token': token,
      };
}

import '../../domain/entities/create_user_info.dart';

class CreateUseInfoModel extends CreateUserInfo {
  const CreateUseInfoModel({
    required super.success,
    required super.message,
    required super.error,
  });

  factory CreateUseInfoModel.fromJson(Map<String, dynamic> json) {
    return CreateUseInfoModel(
      success: json['success'] as bool? ?? false,
      message: json['message'] as String?,
      error: json['error'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {'success': success, 'message': message, 'error': error};
}

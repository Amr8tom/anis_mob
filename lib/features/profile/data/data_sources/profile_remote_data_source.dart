import 'package:dio/dio.dart';

import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../../domain/use_cases/update_profile_use_case.dart';
import '../../domain/entity/subscription_activation_entity.dart';
import '../model/profile_model.dart';

abstract class ProfileRemoteDataSource {
  Future<ProfileModel> getProfile();
  Future<ProfileModel> updateProfile(UpdateProfileParams params);
  Future<SubscriptionActivationEntity> activateSubscriptionCode(String code);
}

class ProfileRemoteDataSourceImpl implements ProfileRemoteDataSource {
  final DioHelper _dio;

  const ProfileRemoteDataSourceImpl(this._dio);

  @override
  Future<ProfileModel> getProfile() async {
    final response = await _dio.get(url: URL.profile, requiresAuth: true);
    return ProfileModel.fromJson(_extractObject(response));
  }

  @override
  Future<ProfileModel> updateProfile(UpdateProfileParams params) async {
    final dynamic data;

    if (params.hasAvatar) {
      // Image upload requires multipart/form-data.
      // Build FormData with all text fields + the avatar file.
      final fields = params.toMap();
      final formMap = <String, dynamic>{
        for (final entry in fields.entries) entry.key: entry.value,
      };

      formMap['avatar'] = await MultipartFile.fromFile(
        params.avatarFile!.path,
        filename: params.avatarFile!.name,
      );

      data = FormData.fromMap(formMap);
    } else {
      // No file — plain JSON patch is fine.
      data = params.toMap();
    }

    final response = await _dio.patch(
      url: URL.profile,
      data: data,
      requiresAuth: true,
    );
    return ProfileModel.fromJson(_extractObject(response));
  }

  @override
  Future<SubscriptionActivationEntity> activateSubscriptionCode(
      String code) async {
    final response = await _dio.post(
      url: URL.activateSubscription,
      data: {'code': code},
      requiresAuth: true,
    );
    if (response is! Map<String, dynamic> ||
        response['data'] is! Map<String, dynamic>) {
      throw const ServerFailure(
          message: 'Unexpected activation response shape');
    }

    final data = response['data'] as Map<String, dynamic>;
    return SubscriptionActivationEntity(
      id: data['id']?.toString() ?? '',
      scope: data['scope']?.toString() ?? 'global',
      workspaceName: data['workspaceName']?.toString(),
      planName: data['planName']?.toString() ?? data['plan']?.toString(),
      remainingMinutes: data['remainingMinutes'] as int?,
      message: response['message']?.toString() ??
          'Subscription activated successfully',
    );
  }

  Map<String, dynamic> _extractObject(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is Map<String, dynamic>) return data;
    }
    throw const ServerFailure(message: 'Unexpected profile response shape');
  }
}

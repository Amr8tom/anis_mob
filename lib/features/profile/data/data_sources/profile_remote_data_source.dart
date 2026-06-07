import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../../domain/use_cases/update_profile_use_case.dart';
import '../model/profile_model.dart';

abstract class ProfileRemoteDataSource {
  Future<ProfileModel> getProfile();
  Future<ProfileModel> updateProfile(UpdateProfileParams params);
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
    final response = await _dio.patch(
      url: URL.profile,
      data: params.toMap(),
      requiresAuth: true,
    );
    return ProfileModel.fromJson(_extractObject(response));
  }

  Map<String, dynamic> _extractObject(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is Map<String, dynamic>) return data;
    }
    throw const ServerFailure(message: 'Unexpected profile response shape');
  }
}

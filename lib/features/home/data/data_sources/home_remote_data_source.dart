import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../model/study_session_model.dart';
import '../model/user_profile_model.dart';

abstract class HomeRemoteDataSource {
  Future<UserProfileModel> getUserProfile();
  Future<List<StudySessionModel>> getTodaySessions();
}

class HomeRemoteDataSourceImpl implements HomeRemoteDataSource {
  final DioHelper _dio;

  const HomeRemoteDataSourceImpl(this._dio);

  @override
  Future<UserProfileModel> getUserProfile() async {
    final response = await _dio.get(
      url: URL.homeProfile,
      requiresAuth: true,
    );
    return UserProfileModel.fromJson(_extractObject(response));
  }

  @override
  Future<List<StudySessionModel>> getTodaySessions() async {
    final response = await _dio.get(
      url: URL.homeTodaySessions,
      requiresAuth: true,
    );
    final items = _extractList(response);
    return items
        .whereType<Map<String, dynamic>>()
        .map(StudySessionModel.fromJson)
        .toList();
  }

  List<dynamic> _extractList(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is List) return data;
    }
    if (response is List) return response;
    throw const ServerFailure(message: 'Unexpected home sessions response shape');
  }

  Map<String, dynamic> _extractObject(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is Map<String, dynamic>) return data;
    }
    throw const ServerFailure(message: 'Unexpected home profile response shape');
  }
}

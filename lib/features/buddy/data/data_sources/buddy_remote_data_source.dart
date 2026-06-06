import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../model/buddy_session_model.dart';

abstract class BuddyRemoteDataSource {
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  });
  Future<BuddySessionModel> getBuddySessionDetails(String sessionId);
  Future<bool> joinSession(String sessionId);
  Future<bool> createSession(Map<String, dynamic> data);
}

class BuddyRemoteDataSourceImpl implements BuddyRemoteDataSource {
  final DioHelper _dio;

  const BuddyRemoteDataSourceImpl(this._dio);

  @override
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    // Public, tokenless listing.
    final response = await _dio.get(
      url: URL.buddySessions,
      queryParameters: <String, dynamic>{
        if (university != null && university.isNotEmpty)
          'university': university,
        if (subject != null && subject.isNotEmpty) 'subject': subject,
        if (filter != null && filter.isNotEmpty && filter != 'all')
          'filter': filter,
      },
      requiresAuth: false,
    );

    return _extractList(response)
        .whereType<Map<String, dynamic>>()
        .map(BuddySessionModel.fromJson)
        .toList();
  }

  @override
  Future<BuddySessionModel> getBuddySessionDetails(String sessionId) async {
    final response = await _dio.get(
      url: URL.buddySessionById(sessionId),
      requiresAuth: false,
    );

    return BuddySessionModel.fromJson(_extractObject(response));
  }

  @override
  Future<bool> joinSession(String sessionId) async {
    // Authenticated action — a non-2xx response throws a typed Failure.
    await _dio.post(url: URL.joinBuddySession(sessionId), requiresAuth: true);
    return true;
  }

  @override
  Future<bool> createSession(Map<String, dynamic> data) async {
    // Authenticated action. The backend ignores workspaceName/workspaceAddress
    // and resolves the workspace from workspaceId only.
    await _dio.post(url: URL.buddySessions, data: data, requiresAuth: true);
    return true;
  }

  List<dynamic> _extractList(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is List) return data;
    }
    if (response is List) return response;
    throw const ServerFailure(
        message: 'Unexpected buddy sessions response shape');
  }

  Map<String, dynamic> _extractObject(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is Map<String, dynamic>) return data;
    }
    throw const ServerFailure(
      message: 'Unexpected buddy session response shape',
    );
  }
}

import '../model/buddy_session_model.dart';

abstract class BuddyRemoteDataSource {
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  });
  Future<bool> joinSession(String sessionId);
  Future<bool> createSession(Map<String, dynamic> data);
}

class BuddyRemoteDataSourceImpl implements BuddyRemoteDataSource {
  @override
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) {
    throw UnimplementedError('Buddy API is not wired yet.');
  }

  @override
  Future<bool> joinSession(String sessionId) {
    throw UnimplementedError('Buddy API is not wired yet.');
  }

  @override
  Future<bool> createSession(Map<String, dynamic> data) {
    throw UnimplementedError('Buddy API is not wired yet.');
  }
}

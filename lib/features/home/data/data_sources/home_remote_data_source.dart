import '../model/study_session_model.dart';
import '../model/user_profile_model.dart';

abstract class HomeRemoteDataSource {
  Future<UserProfileModel> getUserProfile();
  Future<List<StudySessionModel>> getTodaySessions();
}

class HomeRemoteDataSourceImpl implements HomeRemoteDataSource {
  @override
  Future<UserProfileModel> getUserProfile() {
    throw UnimplementedError('Home API is not wired yet.');
  }

  @override
  Future<List<StudySessionModel>> getTodaySessions() {
    throw UnimplementedError('Home API is not wired yet.');
  }
}

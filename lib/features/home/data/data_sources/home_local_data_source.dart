import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/study_session_model.dart';
import '../model/user_profile_model.dart';

abstract class HomeLocalDataSource {
  Future<void> cacheUserProfile(UserProfileModel profile);
  Future<UserProfileModel> getCachedUserProfile();
  Future<void> cacheTodaySessions(List<StudySessionModel> sessions);
  Future<List<StudySessionModel>> getCachedTodaySessions();
}

class HomeLocalDataSourceImpl implements HomeLocalDataSource {
  final LocalStorage storage;

  const HomeLocalDataSourceImpl(this.storage);

  @override
  Future<void> cacheUserProfile(UserProfileModel profile) async {
    await storage.cacheString(
      key: StorageKeys.homeUserProfile.name,
      value: jsonEncode(profile.toJson()),
    );
  }

  @override
  Future<UserProfileModel> getCachedUserProfile() async {
    final cached = storage.getString(key: StorageKeys.homeUserProfile.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return UserProfileModel.fromJson(
      jsonDecode(cached) as Map<String, dynamic>,
    );
  }

  @override
  Future<void> cacheTodaySessions(List<StudySessionModel> sessions) async {
    await storage.cacheString(
      key: StorageKeys.homeTodaySessions.name,
      value: jsonEncode(sessions.map((item) => item.toJson()).toList()),
    );
  }

  @override
  Future<List<StudySessionModel>> getCachedTodaySessions() async {
    final cached = storage.getString(key: StorageKeys.homeTodaySessions.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return (jsonDecode(cached) as List<dynamic>)
        .map((item) => StudySessionModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}

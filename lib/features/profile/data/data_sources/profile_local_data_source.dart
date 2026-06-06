import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/profile_model.dart';

abstract class ProfileLocalDataSource {
  Future<void> cacheProfile(ProfileModel profile);
  Future<ProfileModel> getCachedProfile();
}

class ProfileLocalDataSourceImpl implements ProfileLocalDataSource {
  final LocalStorage storage;

  const ProfileLocalDataSourceImpl(this.storage);

  @override
  Future<void> cacheProfile(ProfileModel profile) async {
    await storage.cacheString(
      key: StorageKeys.profile.name,
      value: jsonEncode(profile.toJson()),
    );
  }

  @override
  Future<ProfileModel> getCachedProfile() async {
    final cached = storage.getString(key: StorageKeys.profile.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return ProfileModel.fromJson(jsonDecode(cached) as Map<String, dynamic>);
  }
}

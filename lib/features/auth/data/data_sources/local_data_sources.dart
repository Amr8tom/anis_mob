import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';

abstract class AuthLocalDataSources {
  Future<String?> getToken();
  Future<void> saveToken(String token);
  Future<void> clearToken();
  Future<void> saveUserInfoDraft({
    required String name,
    required String email,
    required String whatsAppNumber,
    required String gender,
    required String avatar,
    required String university,
    required String major,
    required String year,
  });

  Future<void> saveGuestFlag();
  Future<void> clearGuestFlag();
  Future<bool> isGuest();
}

class AuthLocalDataSourcesImpl implements AuthLocalDataSources {
  final LocalStorage storage;

  const AuthLocalDataSourcesImpl(this.storage);

  @override
  Future<String?> getToken() async =>
      storage.getString(key: StorageKeys.token.name);

  @override
  Future<void> saveToken(String token) async {
    await storage.cacheString(key: StorageKeys.token.name, value: token);
  }

  @override
  Future<void> clearToken() async {
    await storage.remove(key: StorageKeys.token.name);
  }

  @override
  Future<void> saveUserInfoDraft({
    required String name,
    required String email,
    required String whatsAppNumber,
    required String gender,
    required String avatar,
    required String university,
    required String major,
    required String year,
  }) async {
    await Future.wait([
      storage.cacheString(key: StorageKeys.userName.name, value: name),
      storage.cacheString(key: StorageKeys.userEmail.name, value: email),
      storage.cacheString(
        key: StorageKeys.userWhatsApp.name,
        value: whatsAppNumber,
      ),
      storage.cacheString(key: StorageKeys.userGender.name, value: gender),
      storage.cacheString(key: StorageKeys.userAvatar.name, value: avatar),
      storage.cacheString(
        key: StorageKeys.userUniversity.name,
        value: university,
      ),
      storage.cacheString(key: StorageKeys.userMajor.name, value: major),
      storage.cacheString(key: StorageKeys.userYear.name, value: year),
    ]);
  }

  @override
  Future<void> saveGuestFlag() async =>
      storage.cacheBool(key: StorageKeys.isGuest.name, value: true);

  @override
  Future<void> clearGuestFlag() async =>
      storage.cacheBool(key: StorageKeys.isGuest.name, value: false);

  @override
  Future<bool> isGuest() async =>
      storage.getBool(key: StorageKeys.isGuest.name);
}

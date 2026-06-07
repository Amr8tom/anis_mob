import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';

abstract class AuthLocalDataSources {
  Future<String?> getToken();
  Future<void> saveToken(String token);
  Future<void> clearToken();

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
  Future<void> saveGuestFlag() async =>
      storage.cacheBool(key: StorageKeys.isGuest.name, value: true);

  @override
  Future<void> clearGuestFlag() async =>
      storage.cacheBool(key: StorageKeys.isGuest.name, value: false);

  @override
  Future<bool> isGuest() async =>
      storage.getBool(key: StorageKeys.isGuest.name);
}

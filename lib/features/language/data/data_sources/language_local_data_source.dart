import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';

abstract class LanguageLocalDataSource {
  Future<String> getLanguage();

  Future<void> cacheLanguage(String code);
}

class LanguageLocalDataSourceImpl implements LanguageLocalDataSource {
  final LocalStorage storage;

  const LanguageLocalDataSourceImpl(this.storage);

  @override
  Future<String> getLanguage() async {
    final stored = storage.getString(key: StorageKeys.lang.name);
    return stored == null || stored.isEmpty ? 'ar' : stored;
  }

  @override
  Future<void> cacheLanguage(String code) async {
    await storage.cacheString(key: StorageKeys.lang.name, value: code);
  }
}

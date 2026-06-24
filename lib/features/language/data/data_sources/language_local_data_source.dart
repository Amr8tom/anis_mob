import 'dart:ui';

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
    final stored = storage.getString(key: StorageKeys.cachedCode.name) ?? 
                   storage.getString(key: StorageKeys.lang.name);
                   
    if (stored != null && stored.isNotEmpty) {
      return stored;
    }

    final deviceLocale = PlatformDispatcher.instance.locale.languageCode;
    // Supported locales
    if (['en', 'ar', 'tr'].contains(deviceLocale)) {
      return deviceLocale;
    }
    
    return 'ar'; // Default fallback
  }

  @override
  Future<void> cacheLanguage(String code) async {
    await storage.cacheLanguage(code: code);
    await storage.cacheString(key: StorageKeys.lang.name, value: code);
  }
}

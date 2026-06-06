import 'package:shared_preferences/shared_preferences.dart';

import 'local_storage.dart';
import 'storage_keys.dart';

class SharedPreferencesLocalStorage implements LocalStorage {
  final SharedPreferences sharedPreferences;

  const SharedPreferencesLocalStorage(this.sharedPreferences);

  static String get _cachedLanguageKey => StorageKeys.cachedCode.name;
  static String get _isDarkModeKey => StorageKeys.isDarkMode.name;
  static String get _loginModelKey => StorageKeys.loginModel.name;

  @override
  String get cachedLanguage =>
      sharedPreferences.getString(_cachedLanguageKey) ?? 'en';

  @override
  bool get isDarkMode => sharedPreferences.getBool(_isDarkModeKey) ?? false;

  @override
  String? get loginModel => sharedPreferences.getString(_loginModelKey);

  @override
  String? getString({required String key}) => sharedPreferences.getString(key);

  @override
  Future<bool> cacheString({required String key, required String value}) =>
      sharedPreferences.setString(key, value);

  @override
  bool getBool({required String key, bool defaultValue = false}) =>
      sharedPreferences.getBool(key) ?? defaultValue;

  @override
  Future<bool> cacheBool({required String key, required bool value}) =>
      sharedPreferences.setBool(key, value);

  @override
  int getInt({required String key, int defaultValue = 0}) =>
      sharedPreferences.getInt(key) ?? defaultValue;

  @override
  Future<bool> cacheInt({required String key, required int value}) =>
      sharedPreferences.setInt(key, value);

  @override
  double getDouble({required String key, double defaultValue = 0.0}) =>
      sharedPreferences.getDouble(key) ?? defaultValue;

  @override
  Future<bool> cacheDouble({required String key, required double value}) =>
      sharedPreferences.setDouble(key, value);

  @override
  Future<bool> remove({required String key}) => sharedPreferences.remove(key);

  @override
  Future<bool> clear() => sharedPreferences.clear();

  @override
  bool containsKey({required String key}) => sharedPreferences.containsKey(key);

  @override
  Future<void> cacheLanguage({required String code}) async {
    await sharedPreferences.setString(_cachedLanguageKey, code);
  }

  @override
  Future<void> cacheIsDarkMode({required bool isDarkMode}) async {
    await sharedPreferences.setBool(_isDarkModeKey, isDarkMode);
  }

  @override
  Future<void> cacheLoginModel({required String value}) async {
    await sharedPreferences.setString(_loginModelKey, value);
  }
}

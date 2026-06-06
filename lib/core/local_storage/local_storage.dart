abstract interface class LocalStorage {
  String get cachedLanguage;

  bool get isDarkMode;

  String? get loginModel;

  String? getString({required String key});

  Future<bool> cacheString({required String key, required String value});

  bool getBool({required String key, bool defaultValue = false});

  Future<bool> cacheBool({required String key, required bool value});

  int getInt({required String key, int defaultValue = 0});

  Future<bool> cacheInt({required String key, required int value});

  double getDouble({required String key, double defaultValue = 0.0});

  Future<bool> cacheDouble({required String key, required double value});

  Future<bool> remove({required String key});

  Future<bool> clear();

  bool containsKey({required String key});

  Future<void> cacheLanguage({required String code});

  Future<void> cacheIsDarkMode({required bool isDarkMode});

  Future<void> cacheLoginModel({required String value});
}

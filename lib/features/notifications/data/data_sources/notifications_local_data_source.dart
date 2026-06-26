import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../models/notification_preferences_model.dart';

abstract class NotificationsLocalDataSource {
  Future<void> cachePreferences(NotificationPreferencesModel preferences);
  Future<NotificationPreferencesModel> getCachedPreferences();
}

class NotificationsLocalDataSourceImpl implements NotificationsLocalDataSource {
  final LocalStorage _storage;

  const NotificationsLocalDataSourceImpl(this._storage);

  @override
  Future<void> cachePreferences(
      NotificationPreferencesModel preferences) async {
    await _storage.cacheString(
      key: StorageKeys.notificationPreferences.name,
      value: jsonEncode(preferences.toJson()),
    );
  }

  @override
  Future<NotificationPreferencesModel> getCachedPreferences() async {
    final cached = _storage.getString(
      key: StorageKeys.notificationPreferences.name,
    );

    if (cached == null || cached.isEmpty) {
      throw const CacheFailure();
    }

    final decoded = jsonDecode(cached) as Map<String, dynamic>;
    return NotificationPreferencesModel.fromJson(decoded);
  }
}

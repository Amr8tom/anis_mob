import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/buddy_session_model.dart';

abstract class BuddyLocalDataSource {
  Future<void> cacheBuddySessions(List<BuddySessionModel> sessions);
  Future<List<BuddySessionModel>> getCachedBuddySessions();
}

class BuddyLocalDataSourceImpl implements BuddyLocalDataSource {
  final LocalStorage storage;

  const BuddyLocalDataSourceImpl(this.storage);

  @override
  Future<void> cacheBuddySessions(List<BuddySessionModel> sessions) async {
    await storage.cacheString(
      key: StorageKeys.buddySessions.name,
      value: jsonEncode(sessions.map((item) => item.toJson()).toList()),
    );
  }

  @override
  Future<List<BuddySessionModel>> getCachedBuddySessions() async {
    final cached = storage.getString(key: StorageKeys.buddySessions.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return (jsonDecode(cached) as List<dynamic>)
        .map((item) => BuddySessionModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}

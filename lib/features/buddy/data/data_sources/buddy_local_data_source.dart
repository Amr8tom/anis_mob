import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/buddy_session_model.dart';

abstract class BuddyLocalDataSource {
  Future<void> cacheBuddySessions(List<BuddySessionModel> sessions);
  Future<void> cacheBuddySession(BuddySessionModel session);
  Future<List<BuddySessionModel>> getCachedBuddySessions();
  Future<BuddySessionModel> getCachedBuddySession(String sessionId);
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
  Future<void> cacheBuddySession(BuddySessionModel session) async {
    List<BuddySessionModel> sessions;
    try {
      sessions = await getCachedBuddySessions();
    } on CacheFailure {
      sessions = [];
    }

    final index = sessions.indexWhere((item) => item.id == session.id);
    if (index == -1) {
      sessions.add(session);
    } else {
      sessions[index] = session;
    }
    await cacheBuddySessions(sessions);
  }

  @override
  Future<List<BuddySessionModel>> getCachedBuddySessions() async {
    final cached = storage.getString(key: StorageKeys.buddySessions.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return (jsonDecode(cached) as List<dynamic>)
        .map((item) => BuddySessionModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  @override
  Future<BuddySessionModel> getCachedBuddySession(String sessionId) async {
    final sessions = await getCachedBuddySessions();
    try {
      return sessions.firstWhere((session) => session.id == sessionId);
    } on StateError {
      throw const CacheFailure();
    }
  }
}

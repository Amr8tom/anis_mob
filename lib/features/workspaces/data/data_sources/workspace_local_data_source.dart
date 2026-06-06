import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/workspace_model.dart';

abstract class WorkspaceLocalDataSource {
  Future<void> cacheWorkspaces(List<WorkspaceModel> workspaces);
  Future<List<WorkspaceModel>> getCachedWorkspaces();
}

class WorkspaceLocalDataSourceImpl implements WorkspaceLocalDataSource {
  final LocalStorage storage;

  const WorkspaceLocalDataSourceImpl(this.storage);

  @override
  Future<void> cacheWorkspaces(List<WorkspaceModel> workspaces) async {
    await storage.cacheString(
      key: StorageKeys.workspaces.name,
      value: jsonEncode(workspaces.map((item) => item.toJson()).toList()),
    );
  }

  @override
  Future<List<WorkspaceModel>> getCachedWorkspaces() async {
    final cached = storage.getString(key: StorageKeys.workspaces.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return (jsonDecode(cached) as List<dynamic>)
        .map((item) => WorkspaceModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}

import 'dart:convert';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../model/workspace_model.dart';

abstract class WorkspaceLocalDataSource {
  Future<void> cacheWorkspaces(List<WorkspaceModel> workspaces);
  Future<void> cacheWorkspace(WorkspaceModel workspace);
  Future<List<WorkspaceModel>> getCachedWorkspaces();
  Future<WorkspaceModel> getCachedWorkspace(String workspaceId);
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
  Future<void> cacheWorkspace(WorkspaceModel workspace) async {
    List<WorkspaceModel> workspaces;
    try {
      workspaces = await getCachedWorkspaces();
    } on CacheFailure {
      workspaces = [];
    }

    final index = workspaces.indexWhere((item) => item.id == workspace.id);
    if (index == -1) {
      workspaces.add(workspace);
    } else {
      workspaces[index] = workspace;
    }
    await cacheWorkspaces(workspaces);
  }

  @override
  Future<List<WorkspaceModel>> getCachedWorkspaces() async {
    final cached = storage.getString(key: StorageKeys.workspaces.name);
    if (cached == null || cached.isEmpty) throw CacheFailure();

    return (jsonDecode(cached) as List<dynamic>)
        .map((item) => WorkspaceModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  @override
  Future<WorkspaceModel> getCachedWorkspace(String workspaceId) async {
    final workspaces = await getCachedWorkspaces();
    try {
      return workspaces.firstWhere((workspace) => workspace.id == workspaceId);
    } on StateError {
      throw const CacheFailure();
    }
  }
}

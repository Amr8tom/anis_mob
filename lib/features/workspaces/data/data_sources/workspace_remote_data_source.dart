import '../model/workspace_model.dart';

abstract class WorkspaceRemoteDataSource {
  Future<List<WorkspaceModel>> getWorkspaces({String? filter});
}

class WorkspaceRemoteDataSourceImpl implements WorkspaceRemoteDataSource {
  @override
  Future<List<WorkspaceModel>> getWorkspaces({String? filter}) {
    throw UnimplementedError('Workspace API is not wired yet.');
  }
}

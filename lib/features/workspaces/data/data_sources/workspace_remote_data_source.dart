import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../model/workspace_model.dart';

abstract class WorkspaceRemoteDataSource {
  Future<List<WorkspaceModel>> getWorkspaces({String? filter});
}

class WorkspaceRemoteDataSourceImpl implements WorkspaceRemoteDataSource {
  final DioHelper _dio;

  const WorkspaceRemoteDataSourceImpl(this._dio);

  @override
  Future<List<WorkspaceModel>> getWorkspaces({String? filter}) async {
    // Public, tokenless endpoint. DioHelper throws a typed Failure on error.
    final response = await _dio.get(
      url: URL.workspaces,
      queryParameters: filter != null ? {'filter': filter} : null,
      requiresAuth: false,
    );

    final items = _extractList(response);
    return items
        .whereType<Map<String, dynamic>>()
        .map(WorkspaceModel.fromJson)
        .toList();
  }

  /// Unwraps the Laravel paginated envelope: `{ success, message, data: [], meta }`.
  List<dynamic> _extractList(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is List) return data;
    }
    if (response is List) return response;
    throw const ServerFailure(message: 'Unexpected workspaces response shape');
  }
}

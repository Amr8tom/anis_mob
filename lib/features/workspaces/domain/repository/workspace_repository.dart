import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_entity.dart';

abstract class WorkspaceRepository {
  Future<Either<Failure, List<WorkspaceEntity>>> getWorkspaces({
    String? filter, // 'all' | 'openNow' | 'nearby'
  });
  Future<Either<Failure, WorkspaceEntity>> getWorkspaceDetails(
    String workspaceId,
  );
}

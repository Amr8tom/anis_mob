import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_entity.dart';
import '../repository/workspace_repository.dart';

class GetWorkspaceDetailsUseCase {
  final WorkspaceRepository repository;

  const GetWorkspaceDetailsUseCase(this.repository);

  Future<Either<Failure, WorkspaceEntity>> call(String workspaceId) {
    return repository.getWorkspaceDetails(workspaceId);
  }
}

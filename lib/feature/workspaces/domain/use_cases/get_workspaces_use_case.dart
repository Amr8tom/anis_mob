import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_entity.dart';
import '../repository/workspace_repository.dart';

class GetWorkspacesUseCase {
  final WorkspaceRepository repository;
  GetWorkspacesUseCase(this.repository);

  Future<Either<Failure, List<WorkspaceEntity>>> call({String? filter}) =>
      repository.getWorkspaces(filter: filter);
}

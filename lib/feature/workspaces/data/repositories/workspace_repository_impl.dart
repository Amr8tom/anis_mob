import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entity/workspace_entity.dart';
import '../../domain/repository/workspace_repository.dart';
import '../data_sources/workspace_remote_data_source.dart';

class WorkspaceRepositoryImpl implements WorkspaceRepository {
  final WorkspaceRemoteDataSource remoteDataSource;
  WorkspaceRepositoryImpl({required this.remoteDataSource});

  @override
  Future<Either<Failure, List<WorkspaceEntity>>> getWorkspaces({
    String? filter,
  }) async {
    try {
      final result = await remoteDataSource.getWorkspaces(filter: filter);
      return Right(result);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }
}

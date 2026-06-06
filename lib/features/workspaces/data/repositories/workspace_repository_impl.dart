import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/workspace_entity.dart';
import '../../domain/repository/workspace_repository.dart';
import '../data_sources/workspace_local_data_source.dart';
import '../data_sources/workspace_remote_data_source.dart';

class WorkspaceRepositoryImpl implements WorkspaceRepository {
  final WorkspaceRemoteDataSource remoteDataSource;
  final WorkspaceLocalDataSource localDataSource;
  final NetworkInfo networkInfo;

  WorkspaceRepositoryImpl({
    required this.remoteDataSource,
    required this.localDataSource,
    required this.networkInfo,
  });

  @override
  Future<Either<Failure, List<WorkspaceEntity>>> getWorkspaces({
    String? filter,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getWorkspaces(filter: filter);
        await localDataSource.cacheWorkspaces(result);
        return Right(result);
      } on ServerFailure catch (failure) {
        final cached = await _getCachedWorkspaces(filter: filter);
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedWorkspaces(filter: filter);
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedWorkspaces(filter: filter);
  }

  Future<Either<Failure, List<WorkspaceEntity>>> _getCachedWorkspaces({
    String? filter,
  }) async {
    try {
      var cached = await localDataSource.getCachedWorkspaces();
      if (filter == 'openNow') {
        cached = cached
            .where((item) => item.status == WorkspaceStatus.open)
            .toList();
      } else if (filter == 'nearby') {
        cached = cached..sort((a, b) => a.distanceKm.compareTo(b.distanceKm));
      }
      return Right(cached);
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }
}

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
      } on Failure catch (failure) {
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

  @override
  Future<Either<Failure, WorkspaceEntity>> getWorkspaceDetails(
    String workspaceId,
  ) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getWorkspaceDetails(workspaceId);
        await localDataSource.cacheWorkspace(result);
        return Right(result);
      } on Failure catch (failure) {
        final cached = await _getCachedWorkspace(workspaceId);
        return cached.fold((_) => Left(failure), Right.new);
      } catch (error) {
        final cached = await _getCachedWorkspace(workspaceId);
        return cached.fold(
          (_) => Left(ServerFailure(message: error.toString())),
          Right.new,
        );
      }
    }

    return _getCachedWorkspace(workspaceId);
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

  Future<Either<Failure, WorkspaceEntity>> _getCachedWorkspace(
    String workspaceId,
  ) async {
    try {
      return Right(await localDataSource.getCachedWorkspace(workspaceId));
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (_) {
      return const Left(CacheFailure());
    }
  }
}

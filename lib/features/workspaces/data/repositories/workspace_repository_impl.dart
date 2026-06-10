import 'package:dartz/dartz.dart';
import 'package:geolocator/geolocator.dart';

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
    double? latitude,
    double? longitude,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getWorkspaces(
          filter: filter,
          latitude: latitude,
          longitude: longitude,
        );
        await localDataSource.cacheWorkspaces(result);
        return Right(result);
      } on Failure catch (failure) {
        final cached = await _getCachedWorkspaces(
          filter: filter,
          latitude: latitude,
          longitude: longitude,
        );
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedWorkspaces(
          filter: filter,
          latitude: latitude,
          longitude: longitude,
        );
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedWorkspaces(
      filter: filter,
      latitude: latitude,
      longitude: longitude,
    );
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
    double? latitude,
    double? longitude,
  }) async {
    try {
      List<WorkspaceEntity> cached = await localDataSource.getCachedWorkspaces();

      if (latitude != null && longitude != null) {
        cached = cached.map((item) {
          final distanceMeters = Geolocator.distanceBetween(
            latitude,
            longitude,
            item.latitude,
            item.longitude,
          );
          final distanceKm = double.parse((distanceMeters / 1000.0).toStringAsFixed(2));
          return item.copyWith(distanceKm: distanceKm);
        }).toList();
      }

      if (filter == 'openNow') {
        cached = cached
            .where((item) => item.status == WorkspaceStatus.open)
            .toList();
      }

      if (filter == 'nearby' || (latitude != null && longitude != null)) {
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

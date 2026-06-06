import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/repository/profile_repository.dart';
import '../data_sources/profile_local_data_source.dart';
import '../data_sources/profile_remote_data_source.dart';

class ProfileRepositoryImpl implements ProfileRepository {
  final ProfileRemoteDataSource remoteDataSource;
  final ProfileLocalDataSource localDataSource;
  final NetworkInfo networkInfo;

  ProfileRepositoryImpl({
    required this.remoteDataSource,
    required this.localDataSource,
    required this.networkInfo,
  });

  @override
  Future<Either<Failure, ProfileEntity>> getProfile() async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getProfile();
        await localDataSource.cacheProfile(result);
        return Right(result);
      } on ServerFailure catch (failure) {
        final cached = await _getCachedProfile();
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedProfile();
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedProfile();
  }

  Future<Either<Failure, ProfileEntity>> _getCachedProfile() async {
    try {
      return Right(await localDataSource.getCachedProfile());
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }
}

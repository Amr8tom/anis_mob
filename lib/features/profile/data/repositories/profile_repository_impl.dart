import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/entity/subscription_activation_entity.dart';
import '../../domain/repository/profile_repository.dart';
import '../../domain/use_cases/update_profile_use_case.dart';
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
      } on Failure catch (failure) {
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

  @override
  Future<Either<Failure, ProfileEntity>> updateProfile(
    UpdateProfileParams params,
  ) async {
    if (!await networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      final result = await remoteDataSource.updateProfile(params);
      await localDataSource.cacheProfile(result);
      return Right(result);
    } on Failure catch (failure) {
      return Left(failure);
    } catch (error) {
      return Left(ServerFailure(message: error.toString()));
    }
  }

  @override
  Future<Either<Failure, SubscriptionActivationEntity>>
      activateSubscriptionCode(String code) async {
    if (!await networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      return Right(await remoteDataSource.activateSubscriptionCode(code));
    } on Failure catch (failure) {
      return Left(failure);
    } catch (error) {
      return Left(ServerFailure(message: error.toString()));
    }
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

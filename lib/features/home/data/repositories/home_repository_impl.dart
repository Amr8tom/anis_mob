import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/study_session_entity.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../../domain/repository/home_repository.dart';
import '../data_sources/home_local_data_source.dart';
import '../data_sources/home_remote_data_source.dart';

class HomeRepositoryImpl implements HomeRepository {
  final HomeRemoteDataSource remoteDataSource;
  final HomeLocalDataSource localDataSource;
  final NetworkInfo networkInfo;

  HomeRepositoryImpl({
    required this.remoteDataSource,
    required this.localDataSource,
    required this.networkInfo,
  });

  @override
  Future<Either<Failure, UserProfileEntity>> getUserProfile() async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getUserProfile();
        await localDataSource.cacheUserProfile(result);
        return Right(result);
      } on ServerFailure catch (failure) {
        final cached = await _getCachedUserProfile();
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedUserProfile();
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedUserProfile();
  }

  @override
  Future<Either<Failure, List<StudySessionEntity>>> getTodaySessions() async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getTodaySessions();
        await localDataSource.cacheTodaySessions(result);
        return Right(result);
      } on ServerFailure catch (failure) {
        final cached = await _getCachedTodaySessions();
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedTodaySessions();
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedTodaySessions();
  }

  Future<Either<Failure, UserProfileEntity>> _getCachedUserProfile() async {
    try {
      return Right(await localDataSource.getCachedUserProfile());
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }

  Future<Either<Failure, List<StudySessionEntity>>>
      _getCachedTodaySessions() async {
    try {
      return Right(await localDataSource.getCachedTodaySessions());
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }
}

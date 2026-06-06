import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/repository/buddy_repository.dart';
import '../data_sources/buddy_local_data_source.dart';
import '../data_sources/buddy_remote_data_source.dart';

class BuddyRepositoryImpl implements BuddyRepository {
  final BuddyRemoteDataSource remoteDataSource;
  final BuddyLocalDataSource localDataSource;
  final NetworkInfo networkInfo;

  BuddyRepositoryImpl({
    required this.remoteDataSource,
    required this.localDataSource,
    required this.networkInfo,
  });

  @override
  Future<Either<Failure, List<BuddySessionEntity>>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getBuddySessions(
          university: university,
          subject: subject,
          filter: filter,
        );
        await localDataSource.cacheBuddySessions(result);
        return Right(result);
      } on Failure catch (failure) {
        final cached = await _getCachedSessions(
          university: university,
          subject: subject,
          filter: filter,
        );
        return cached.fold((_) => Left(failure), Right.new);
      } catch (e) {
        final cached = await _getCachedSessions(
          university: university,
          subject: subject,
          filter: filter,
        );
        return cached.fold(
          (_) => Left(ServerFailure(message: e.toString())),
          Right.new,
        );
      }
    }

    return _getCachedSessions(
      university: university,
      subject: subject,
      filter: filter,
    );
  }

  @override
  Future<Either<Failure, bool>> joinSession(String sessionId) async {
    if (!await networkInfo.isConnected) return Left(CacheFailure());

    try {
      final result = await remoteDataSource.joinSession(sessionId);
      return Right(result);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }

  @override
  Future<Either<Failure, bool>> createSession(Map<String, dynamic> data) async {
    if (!await networkInfo.isConnected) return Left(CacheFailure());

    try {
      final result = await remoteDataSource.createSession(data);
      return Right(result);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }

  Future<Either<Failure, List<BuddySessionEntity>>> _getCachedSessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    try {
      var cached = await localDataSource.getCachedBuddySessions();
      if (university != null && university.isNotEmpty) {
        cached = cached
            .where((session) => session.university.contains(university))
            .toList();
      }
      if (subject != null && subject.isNotEmpty) {
        cached = cached
            .where((session) => session.subject.contains(subject))
            .toList();
      }
      if (filter == 'availableNow') {
        cached = cached
            .where(
                (session) => session.availability == BuddyAvailability.online)
            .toList();
      }
      if (filter == 'open') {
        cached = cached
            .where(
                (session) => session.sessionStatus == BuddySessionStatus.open)
            .toList();
      }
      return Right(cached);
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }
}

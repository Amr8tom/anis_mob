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
  Future<Either<Failure, BuddySessionEntity>> getBuddySessionDetails(
    String sessionId,
  ) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getBuddySessionDetails(sessionId);
        await localDataSource.cacheBuddySession(result);
        return Right(result);
      } on Failure catch (failure) {
        final cached = await _getCachedSession(sessionId);
        return cached.fold((_) => Left(failure), Right.new);
      } catch (error) {
        final cached = await _getCachedSession(sessionId);
        return cached.fold(
          (_) => Left(ServerFailure(message: error.toString())),
          Right.new,
        );
      }
    }

    return _getCachedSession(sessionId);
  }

  @override
  Future<Either<Failure, bool>> joinSession(String sessionId) async {
    if (!await networkInfo.isConnected) return Left(const CacheFailure());

    try {
      final result = await remoteDataSource.joinSession(sessionId);
      return Right(result);
    } on Failure catch (failure) {
      // Preserve typed failures (e.g. ConflictFailure for full/already-joined).
      return Left(failure);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }

  @override
  Future<Either<Failure, bool>> createSession(Map<String, dynamic> data) async {
    if (!await networkInfo.isConnected) return Left(const CacheFailure());

    try {
      final result = await remoteDataSource.createSession(data);
      return Right(result);
    } on Failure catch (failure) {
      return Left(failure);
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
      if (filter == 'today') {
        final now = DateTime.now();
        cached = cached
            .where(
              (session) =>
                  session.startTime.year == now.year &&
                  session.startTime.month == now.month &&
                  session.startTime.day == now.day,
            )
            .toList();
      }
      if (filter == 'thisWeek') {
        final now = DateTime.now();
        final start = DateTime(now.year, now.month, now.day)
            .subtract(Duration(days: now.weekday - DateTime.monday));
        final end = start.add(const Duration(days: 7));
        cached = cached
            .where(
              (session) =>
                  !session.startTime.isBefore(start) &&
                  session.startTime.isBefore(end),
            )
            .toList();
      }
      return Right(cached);
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (e) {
      return Left(CacheFailure());
    }
  }

  Future<Either<Failure, BuddySessionEntity>> _getCachedSession(
    String sessionId,
  ) async {
    try {
      return Right(await localDataSource.getCachedBuddySession(sessionId));
    } on CacheFailure catch (failure) {
      return Left(failure);
    } catch (_) {
      return const Left(CacheFailure());
    }
  }
}

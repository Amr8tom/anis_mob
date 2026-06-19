import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entity/workspace_attendance_entity.dart';
import '../../domain/repository/workspace_attendance_repository.dart';
import '../data_sources/workspace_attendance_remote_data_source.dart';

class WorkspaceAttendanceRepositoryImpl
    implements WorkspaceAttendanceRepository {
  final WorkspaceAttendanceRemoteDataSource remoteDataSource;
  final NetworkInfo networkInfo;

  WorkspaceAttendanceRepositoryImpl({
    required this.remoteDataSource,
    required this.networkInfo,
  });

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.checkIn(
          qrPayload: qrPayload,
          checkInTime: checkInTime,
        );
        return Right(result);
      } on Failure catch (failure) {
        return Left(failure);
      } catch (e) {
        return Left(ServerFailure(message: e.toString()));
      }
    }
    return const Left(NetworkFailure(message: 'No internet connection'));
  }

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.checkOut(
          attendanceId: attendanceId,
          workspaceId: workspaceId,
          workspaceName: workspaceName,
          checkInTime: checkInTime,
          elapsedTime: elapsedTime,
        );
        return Right(result);
      } on Failure catch (failure) {
        return Left(failure);
      } catch (e) {
        return Left(ServerFailure(message: e.toString()));
      }
    }
    return const Left(NetworkFailure(message: 'No internet connection'));
  }

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity>> requestCheckout({
    required String attendanceId,
  }) async {
    if (await networkInfo.isConnected) {
      try {
        final result =
            await remoteDataSource.requestCheckout(attendanceId: attendanceId);
        return Right(result);
      } on Failure catch (failure) {
        return Left(failure);
      } catch (e) {
        return Left(ServerFailure(message: e.toString()));
      }
    }
    return const Left(NetworkFailure(message: 'No internet connection'));
  }

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity?>> getActiveVisit() async {
    if (await networkInfo.isConnected) {
      try {
        final result = await remoteDataSource.getActiveVisit();
        return Right(result);
      } on Failure catch (failure) {
        return Left(failure);
      } catch (e) {
        return Left(ServerFailure(message: e.toString()));
      }
    }
    return const Left(NetworkFailure(message: 'No internet connection'));
  }
}

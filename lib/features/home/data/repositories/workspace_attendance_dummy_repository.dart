import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entity/workspace_attendance_entity.dart';
import '../../domain/repository/workspace_attendance_repository.dart';
import '../data_sources/workspace_attendance_remote_data_source.dart';

/// Dummy repository that routes all calls through the dummy data source.
/// When the real API is ready:
///   1. Swap this registration in HomeServiceLocator for the real impl.
///   2. Only the DI registration changes; domain + presentation stay stable.
class WorkspaceAttendanceDummyRepository
    implements WorkspaceAttendanceRepository {
  final WorkspaceAttendanceRemoteDataSource _dataSource;

  const WorkspaceAttendanceDummyRepository(this._dataSource);

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  }) async {
    try {
      final result = await _dataSource.checkIn(
        qrPayload: qrPayload,
        checkInTime: checkInTime,
      );
      return Right(result);
    } on FormatException catch (e) {
      return Left(ServerFailure(message: e.message));
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }

  @override
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  }) async {
    try {
      final result = await _dataSource.checkOut(
        attendanceId: attendanceId,
        workspaceId: workspaceId,
        workspaceName: workspaceName,
        checkInTime: checkInTime,
        elapsedTime: elapsedTime,
      );
      return Right(result);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }
}

import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_attendance_entity.dart';

/// Repository contract for workspace QR check-in / check-out.
/// Domain never imports data sources or models.
abstract class WorkspaceAttendanceRepository {
  /// Register the user's check-in for the workspace identified by [qrPayload].
  /// Returns the active [WorkspaceAttendanceEntity] on success.
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  });

  /// Register the user's check-out.
  /// The server computes [studyMinutes] and deducts it from the subscription.
  /// Returns the completed [WorkspaceAttendanceEntity] with [checkOutTime] set.
  Future<Either<Failure, WorkspaceAttendanceEntity>> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  });
}

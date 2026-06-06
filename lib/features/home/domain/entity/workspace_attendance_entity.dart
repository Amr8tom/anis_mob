import 'package:equatable/equatable.dart';

/// Represents one workspace attendance session.
/// Returned by check-in and check-out use cases.
class WorkspaceAttendanceEntity extends Equatable {
  /// Server-issued attendance record ID.
  final String attendanceId;

  /// Workspace QR payload / workspace identifier.
  final String workspaceId;

  /// Human-readable workspace name.
  final String workspaceName;

  /// When the user checked in (UTC).
  final DateTime checkInTime;

  /// When the user checked out — null while still inside.
  final DateTime? checkOutTime;

  /// Total study minutes as computed by the server after check-out.
  final int? studyMinutes;

  const WorkspaceAttendanceEntity({
    required this.attendanceId,
    required this.workspaceId,
    required this.workspaceName,
    required this.checkInTime,
    this.checkOutTime,
    this.studyMinutes,
  });

  /// Whether the session is still active (no check-out yet).
  bool get isActive => checkOutTime == null;

  /// Elapsed time since check-in (local estimate before server confirms).
  Duration get elapsed => DateTime.now().difference(checkInTime);

  @override
  List<Object?> get props => [
        attendanceId,
        workspaceId,
        workspaceName,
        checkInTime,
        checkOutTime,
        studyMinutes,
      ];
}

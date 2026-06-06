import '../model/workspace_attendance_model.dart';

/// Contract for workspace attendance remote API calls.
/// When the real API is ready: inject DioHelper, replace method bodies.
abstract class WorkspaceAttendanceRemoteDataSource {
  /// POST /workspace/check-in  { qr_payload }
  Future<WorkspaceAttendanceModel> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  });

  /// POST /workspace/check-out  { attendance_id }
  Future<WorkspaceAttendanceModel> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  });
}

/// Dummy implementation.
/// Returns hardcoded data. When real API is ready:
///   1. Inject DioHelper
///   2. Replace each method body with an actual HTTP call
///   3. No other layer needs to change.
///
class WorkspaceAttendanceDummyDataSourceImpl
    implements WorkspaceAttendanceRemoteDataSource {
  @override
  Future<WorkspaceAttendanceModel> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  }) async {
    await Future.delayed(const Duration(milliseconds: 900));
    if (qrPayload.trim().isEmpty || qrPayload.trim().length < 4) {
      throw const FormatException('invalidWorkspaceQr');
    }
    return WorkspaceAttendanceModel(
      attendanceId: 'att_${DateTime.now().millisecondsSinceEpoch}',
      workspaceId: qrPayload.trim(),
      workspaceName: 'StudyHub Cairo',
      checkInTime: checkInTime,
    );
  }

  @override
  Future<WorkspaceAttendanceModel> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  }) async {
    await Future.delayed(const Duration(milliseconds: 900));
    final checkOut = DateTime.now();
    return WorkspaceAttendanceModel(
      attendanceId: attendanceId,
      workspaceId: workspaceId,
      workspaceName: workspaceName,
      checkInTime: checkInTime,
      checkOutTime: checkOut,
      studyMinutes: elapsedTime.inMinutes.clamp(1, 24 * 60),
    );
  }
}

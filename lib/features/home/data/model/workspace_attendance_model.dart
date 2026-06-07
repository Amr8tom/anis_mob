import '../../domain/entity/workspace_attendance_entity.dart';

class WorkspaceAttendanceModel extends WorkspaceAttendanceEntity {
  const WorkspaceAttendanceModel({
    required super.attendanceId,
    required super.workspaceId,
    required super.workspaceName,
    required super.checkInTime,
    super.checkOutTime,
    super.studyMinutes,
    super.billableMinutes,
    super.deductedMinutes,
    super.hourMultiplierApplied,
  });

  factory WorkspaceAttendanceModel.fromJson(Map<String, dynamic> json) {
    return WorkspaceAttendanceModel(
      attendanceId: json['attendance_id'] as String,
      workspaceId: json['workspace_id'] as String,
      workspaceName: json['workspace_name'] as String,
      checkInTime: DateTime.parse(json['check_in_time'] as String),
      checkOutTime: json['check_out_time'] != null
          ? DateTime.parse(json['check_out_time'] as String)
          : null,
      studyMinutes: json['study_minutes'] as int?,
      billableMinutes: json['billable_minutes'] as int?,
      deductedMinutes: json['deducted_minutes'] as int?,
      hourMultiplierApplied:
          (json['hour_multiplier_applied'] as num?)?.toDouble(),
    );
  }

  Map<String, dynamic> toJson() => toJsonFromEntity(this);

  static Map<String, dynamic> toJsonFromEntity(
      WorkspaceAttendanceEntity entity) {
    return {
      'attendance_id': entity.attendanceId,
      'workspace_id': entity.workspaceId,
      'workspace_name': entity.workspaceName,
      'check_in_time': entity.checkInTime.toIso8601String(),
      'check_out_time': entity.checkOutTime?.toIso8601String(),
      'study_minutes': entity.studyMinutes,
      'billable_minutes': entity.billableMinutes,
      'deducted_minutes': entity.deductedMinutes,
      'hour_multiplier_applied': entity.hourMultiplierApplied,
    };
  }
}

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
    super.billingSource,
    super.workspaceSubscriptionRemainingMinutes,
    super.workspaceSubscriptionExpiresAt,
    super.checkoutMode,
    super.checkoutRequestedAt,
    super.canCheckOutDirectly,
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
      billingSource: (json['billing_source'] as String?) ?? 'FREE',
      workspaceSubscriptionRemainingMinutes:
          json['workspace_subscription_remaining_minutes'] as int?,
      workspaceSubscriptionExpiresAt:
          json['workspace_subscription_expires_at'] != null
              ? DateTime.tryParse(
                  json['workspace_subscription_expires_at'] as String)
              : null,
      checkoutMode: (json['checkout_mode'] as String?) ?? 'DIRECT',
      checkoutRequestedAt: json['checkout_requested_at'] != null
          ? DateTime.tryParse(json['checkout_requested_at'] as String)
          : null,
      // Default true preserves legacy direct-checkout behaviour if absent.
      canCheckOutDirectly: (json['can_check_out_directly'] as bool?) ?? true,
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
      'billing_source': entity.billingSource,
      'workspace_subscription_remaining_minutes':
          entity.workspaceSubscriptionRemainingMinutes,
      'workspace_subscription_expires_at':
          entity.workspaceSubscriptionExpiresAt?.toIso8601String(),
      'checkout_mode': entity.checkoutMode,
      'checkout_requested_at': entity.checkoutRequestedAt?.toIso8601String(),
      'can_check_out_directly': entity.canCheckOutDirectly,
    };
  }
}

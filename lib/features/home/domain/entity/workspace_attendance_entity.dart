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

  /// Billed real minutes (after cap check)
  final int? billableMinutes;

  /// Deducted subscription minutes (after multiplier)
  final int? deductedMinutes;

  /// Multiplier snapshot applied
  final double? hourMultiplierApplied;

  /// Captured server funding source for this visit.
  final String billingSource;

  /// Remaining balance of the workspace-scoped subscription, when applicable.
  final int? workspaceSubscriptionRemainingMinutes;

  final DateTime? workspaceSubscriptionExpiresAt;

  /// Workspace checkout mode: 'DIRECT' or 'APPROVAL'.
  final String checkoutMode;

  /// When the user requested checkout (approval mode) — null otherwise.
  final DateTime? checkoutRequestedAt;

  /// Whether the user can check out instantly (free tier or DIRECT workspace).
  /// Defaults to true so older payloads keep the legacy direct behaviour.
  final bool canCheckOutDirectly;

  const WorkspaceAttendanceEntity({
    required this.attendanceId,
    required this.workspaceId,
    required this.workspaceName,
    required this.checkInTime,
    this.checkOutTime,
    this.studyMinutes,
    this.billableMinutes,
    this.deductedMinutes,
    this.hourMultiplierApplied,
    this.billingSource = 'FREE',
    this.workspaceSubscriptionRemainingMinutes,
    this.workspaceSubscriptionExpiresAt,
    this.checkoutMode = 'DIRECT',
    this.checkoutRequestedAt,
    this.canCheckOutDirectly = true,
  });

  /// Whether the session is still active (no check-out yet).
  bool get isActive => checkOutTime == null;

  /// Whether a checkout request is pending owner approval.
  bool get isCheckoutPending =>
      checkOutTime == null && checkoutRequestedAt != null;

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
        billableMinutes,
        deductedMinutes,
        hourMultiplierApplied,
        billingSource,
        workspaceSubscriptionRemainingMinutes,
        workspaceSubscriptionExpiresAt,
        checkoutMode,
        checkoutRequestedAt,
        canCheckOutDirectly,
      ];
}

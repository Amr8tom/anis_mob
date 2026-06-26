part of 'notifications_cubit.dart';

enum NotificationsStatus {
  initial,
  syncing,
  synced,
  permissionDenied,
  error,
}

extension NotificationsStatusX on NotificationsState {
  bool get isSyncing => status == NotificationsStatus.syncing;
  bool get isSynced => status == NotificationsStatus.synced;
  bool get isPermissionDenied => status == NotificationsStatus.permissionDenied;
  bool get isError => status == NotificationsStatus.error;
}

class NotificationsState extends Equatable {
  final NotificationsStatus status;
  final bool? permissionGranted;
  final String? errorMessage;

  const NotificationsState({
    this.status = NotificationsStatus.initial,
    this.permissionGranted,
    this.errorMessage,
  });

  NotificationsState copyWith({
    NotificationsStatus? status,
    bool? permissionGranted,
    String? errorMessage,
  }) {
    return NotificationsState(
      status: status ?? this.status,
      permissionGranted: permissionGranted ?? this.permissionGranted,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [status, permissionGranted, errorMessage];
}

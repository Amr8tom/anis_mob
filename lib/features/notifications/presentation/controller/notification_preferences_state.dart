part of 'notification_preferences_cubit.dart';

enum NotificationPreferencesStatus {
  initial,
  loading,
  loaded,
  saving,
  saved,
  error,
}

extension NotificationPreferencesStatusX on NotificationPreferencesStatus {
  bool get isLoading => this == NotificationPreferencesStatus.loading;
  bool get isSaving => this == NotificationPreferencesStatus.saving;
  bool get isLoaded =>
      this == NotificationPreferencesStatus.loaded ||
      this == NotificationPreferencesStatus.saving ||
      this == NotificationPreferencesStatus.saved;
  bool get isSaved => this == NotificationPreferencesStatus.saved;
  bool get isError => this == NotificationPreferencesStatus.error;
}

class NotificationPreferencesState extends Equatable {
  final NotificationPreferencesStatus status;
  final NotificationPreferencesEntity preferences;
  final String? errorMessage;

  const NotificationPreferencesState({
    this.status = NotificationPreferencesStatus.initial,
    this.preferences = const NotificationPreferencesEntity.defaults(),
    this.errorMessage,
  });

  NotificationPreferencesState copyWith({
    NotificationPreferencesStatus? status,
    NotificationPreferencesEntity? preferences,
    String? errorMessage,
  }) {
    return NotificationPreferencesState(
      status: status ?? this.status,
      preferences: preferences ?? this.preferences,
      errorMessage: errorMessage,
    );
  }

  @override
  List<Object?> get props => [status, preferences, errorMessage];
}

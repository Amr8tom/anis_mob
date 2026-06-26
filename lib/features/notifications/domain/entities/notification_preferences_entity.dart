import 'package:equatable/equatable.dart';

class NotificationPreferencesEntity extends Equatable {
  final bool sessionReminders;
  final bool subscriptionAlerts;
  final bool offersMarketing;
  final bool workspaceUpdates;

  const NotificationPreferencesEntity({
    required this.sessionReminders,
    required this.subscriptionAlerts,
    required this.offersMarketing,
    required this.workspaceUpdates,
  });

  const NotificationPreferencesEntity.defaults()
      : sessionReminders = true,
        subscriptionAlerts = true,
        offersMarketing = true,
        workspaceUpdates = true;

  NotificationPreferencesEntity copyWith({
    bool? sessionReminders,
    bool? subscriptionAlerts,
    bool? offersMarketing,
    bool? workspaceUpdates,
  }) {
    return NotificationPreferencesEntity(
      sessionReminders: sessionReminders ?? this.sessionReminders,
      subscriptionAlerts: subscriptionAlerts ?? this.subscriptionAlerts,
      offersMarketing: offersMarketing ?? this.offersMarketing,
      workspaceUpdates: workspaceUpdates ?? this.workspaceUpdates,
    );
  }

  @override
  List<Object?> get props => [
        sessionReminders,
        subscriptionAlerts,
        offersMarketing,
        workspaceUpdates,
      ];
}

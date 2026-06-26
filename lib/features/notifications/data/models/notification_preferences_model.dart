import '../../domain/entities/notification_preferences_entity.dart';

class NotificationPreferencesModel extends NotificationPreferencesEntity {
  const NotificationPreferencesModel({
    required super.sessionReminders,
    required super.subscriptionAlerts,
    required super.offersMarketing,
    required super.workspaceUpdates,
  });

  factory NotificationPreferencesModel.fromJson(Map<String, dynamic> json) {
    return NotificationPreferencesModel(
      sessionReminders: _asBool(json['session_reminders']),
      subscriptionAlerts: _asBool(json['subscription_alerts']),
      offersMarketing: _asBool(json['offers_marketing']),
      workspaceUpdates: _asBool(json['workspace_updates']),
    );
  }

  factory NotificationPreferencesModel.fromEntity(
    NotificationPreferencesEntity entity,
  ) {
    return NotificationPreferencesModel(
      sessionReminders: entity.sessionReminders,
      subscriptionAlerts: entity.subscriptionAlerts,
      offersMarketing: entity.offersMarketing,
      workspaceUpdates: entity.workspaceUpdates,
    );
  }

  Map<String, dynamic> toJson() => toJsonFromEntity(this);

  static Map<String, dynamic> toJsonFromEntity(
    NotificationPreferencesEntity entity,
  ) {
    return {
      'session_reminders': entity.sessionReminders,
      'subscription_alerts': entity.subscriptionAlerts,
      'offers_marketing': entity.offersMarketing,
      'workspace_updates': entity.workspaceUpdates,
    };
  }

  static bool _asBool(Object? value) {
    if (value is bool) return value;
    if (value is num) return value != 0;
    if (value is String) return value == '1' || value.toLowerCase() == 'true';
    return true;
  }
}

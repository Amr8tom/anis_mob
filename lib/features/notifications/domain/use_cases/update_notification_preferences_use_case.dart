import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entities/notification_preferences_entity.dart';
import '../repositories/notifications_repository.dart';

class UpdateNotificationPreferencesUseCase extends UseCase<
    NotificationPreferencesEntity, UpdateNotificationPreferencesParams> {
  final NotificationsRepository repository;

  UpdateNotificationPreferencesUseCase(this.repository);

  @override
  Future<Either<Failure, NotificationPreferencesEntity>> call({
    required UpdateNotificationPreferencesParams params,
  }) {
    return repository.updateNotificationPreferences(params: params);
  }
}

class UpdateNotificationPreferencesParams {
  final NotificationPreferencesEntity preferences;

  const UpdateNotificationPreferencesParams({required this.preferences});

  Map<String, dynamic> toMap() {
    return {
      'session_reminders': preferences.sessionReminders,
      'subscription_alerts': preferences.subscriptionAlerts,
      'offers_marketing': preferences.offersMarketing,
      'workspace_updates': preferences.workspaceUpdates,
    };
  }
}

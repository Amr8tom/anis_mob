import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entities/notification_preferences_entity.dart';
import '../use_cases/register_device_token_use_case.dart';
import '../use_cases/track_notification_event_use_case.dart';
import '../use_cases/unregister_device_token_use_case.dart';
import '../use_cases/update_notification_preferences_use_case.dart';

/// Contract for syncing the current device's push token with the backend.
/// Both operations are online-only writes (no offline queue is required).
abstract class NotificationsRepository {
  Future<Either<Failure, Unit>> registerDeviceToken({
    required RegisterDeviceTokenParams params,
  });

  Future<Either<Failure, Unit>> unregisterDeviceToken({
    required UnregisterDeviceTokenParams params,
  });

  Future<Either<Failure, NotificationPreferencesEntity>>
      getNotificationPreferences();

  Future<Either<Failure, NotificationPreferencesEntity>>
      updateNotificationPreferences({
    required UpdateNotificationPreferencesParams params,
  });

  Future<Either<Failure, Unit>> trackNotificationEvent({
    required TrackNotificationEventParams params,
  });
}

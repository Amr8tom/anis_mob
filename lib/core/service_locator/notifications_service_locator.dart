import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:get_it/get_it.dart';

import '../../features/notifications/data/data_sources/notifications_remote_data_source.dart';
import '../../features/notifications/data/data_sources/notifications_local_data_source.dart';
import '../../features/notifications/data/repositories/notifications_repository_impl.dart';
import '../../features/notifications/domain/repositories/notifications_repository.dart';
import '../../features/notifications/domain/use_cases/get_notification_preferences_use_case.dart';
import '../../features/notifications/domain/use_cases/register_device_token_use_case.dart';
import '../../features/notifications/domain/use_cases/track_notification_event_use_case.dart';
import '../../features/notifications/domain/use_cases/unregister_device_token_use_case.dart';
import '../../features/notifications/domain/use_cases/update_notification_preferences_use_case.dart';
import '../../features/notifications/presentation/controller/notification_preferences_cubit.dart';
import '../../features/notifications/presentation/controller/notifications_cubit.dart';
import '../notifications/notification_deep_link_router.dart';
import '../notifications/push_messaging_service.dart';

class NotificationsServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Platform infrastructure ───────────────────────────────────────────────
    serviceLocator.registerLazySingleton<PushMessagingService>(
      () => FirebasePushMessagingService(
        FirebaseMessaging.instance,
        FlutterLocalNotificationsPlugin(),
      ),
    );
    serviceLocator.registerLazySingleton<NotificationDeepLinkRouter>(
      () => const NotificationDeepLinkRouter(),
    );

    // ── Data sources ──────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<NotificationsRemoteDataSource>(
      () => NotificationsRemoteDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<NotificationsLocalDataSource>(
      () => NotificationsLocalDataSourceImpl(serviceLocator()),
    );

    // ── Repository ────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<NotificationsRepository>(
      () => NotificationsRepositoryImpl(
        serviceLocator(),
        serviceLocator(),
        serviceLocator(),
      ),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton(
      () => RegisterDeviceTokenUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => UnregisterDeviceTokenUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => GetNotificationPreferencesUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => UpdateNotificationPreferencesUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => TrackNotificationEventUseCase(serviceLocator()),
    );

    // ── Cubit (long-lived: owns the device token lifecycle for the session) ───
    serviceLocator.registerLazySingleton(
      () => NotificationsCubit(
        serviceLocator(),
        serviceLocator(),
        serviceLocator(),
        serviceLocator(),
        serviceLocator(),
      ),
    );
    serviceLocator.registerFactory(
      () => NotificationPreferencesCubit(serviceLocator(), serviceLocator()),
    );
  }
}

import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../models/notification_preferences_model.dart';
import '../../domain/use_cases/track_notification_event_use_case.dart';
import '../../domain/use_cases/update_notification_preferences_use_case.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';

abstract class NotificationsRemoteDataSource {
  Future<void> registerDeviceToken(RegisterDeviceTokenParams params);
  Future<void> unregisterDeviceToken(UnregisterDeviceTokenParams params);
  Future<NotificationPreferencesModel> getNotificationPreferences();
  Future<NotificationPreferencesModel> updateNotificationPreferences(
    UpdateNotificationPreferencesParams params,
  );
  Future<void> trackNotificationEvent(TrackNotificationEventParams params);
}

class NotificationsRemoteDataSourceImpl
    implements NotificationsRemoteDataSource {
  final DioHelper _dio;

  const NotificationsRemoteDataSourceImpl(this._dio);

  @override
  Future<void> registerDeviceToken(RegisterDeviceTokenParams params) async {
    // Authenticated endpoint: the Bearer token is attached by DioHelper.
    // Any error surfaces as a typed Failure thrown by DioHelper.
    await _dio.post(
      url: URL.deviceTokens,
      data: params.toMap(),
      requiresAuth: true,
    );
  }

  @override
  Future<void> unregisterDeviceToken(UnregisterDeviceTokenParams params) async {
    await _dio.delete(
      url: URL.deviceTokens,
      data: params.toMap(),
      requiresAuth: true,
    );
  }

  @override
  Future<NotificationPreferencesModel> getNotificationPreferences() async {
    final response = await _dio.get(
      url: URL.notificationPreferences,
      requiresAuth: true,
    );

    final data = response is Map<String, dynamic>
        ? response['data'] as Map<String, dynamic>
        : <String, dynamic>{};

    return NotificationPreferencesModel.fromJson(data);
  }

  @override
  Future<NotificationPreferencesModel> updateNotificationPreferences(
    UpdateNotificationPreferencesParams params,
  ) async {
    final response = await _dio.patch(
      url: URL.notificationPreferences,
      data: params.toMap(),
      requiresAuth: true,
    );

    final data = response is Map<String, dynamic>
        ? response['data'] as Map<String, dynamic>
        : <String, dynamic>{};

    return NotificationPreferencesModel.fromJson(data);
  }

  @override
  Future<void> trackNotificationEvent(
    TrackNotificationEventParams params,
  ) async {
    await _dio.post(
      url: URL.notificationEvents,
      data: params.toMap(),
      requiresAuth: true,
    );
  }
}

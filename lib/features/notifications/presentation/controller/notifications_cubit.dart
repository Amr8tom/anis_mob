import 'dart:async';
import 'dart:io';

import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/notifications/push_messaging_service.dart';
import '../../../../core/notifications/notification_deep_link_router.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/track_notification_event_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';

part 'notifications_state.dart';

/// Long-lived coordinator for the device's push token. It owns the side-effect
/// chain "permission → fetch token → register with backend", keeps the backend
/// in sync when FCM rotates the token, and clears it on sign-out.
class NotificationsCubit extends Cubit<NotificationsState> {
  final PushMessagingService _messaging;
  final RegisterDeviceTokenUseCase _registerDeviceToken;
  final UnregisterDeviceTokenUseCase _unregisterDeviceToken;
  final TrackNotificationEventUseCase _trackNotificationEvent;
  final NotificationDeepLinkRouter _deepLinkRouter;

  StreamSubscription<String>? _tokenRefreshSubscription;
  StreamSubscription<Map<String, dynamic>>? _notificationOpenedSubscription;
  bool _started = false;

  NotificationsCubit(
    this._messaging,
    this._registerDeviceToken,
    this._unregisterDeviceToken,
    this._trackNotificationEvent,
    this._deepLinkRouter,
  ) : super(const NotificationsState()) {
    // Keep the backend in sync whenever FCM issues a new token for this device.
    _tokenRefreshSubscription = _messaging.onTokenRefresh.listen(_sendToken);
  }

  /// Starts notification tap handling. Safe to call more than once.
  Future<void> start() async {
    if (_started) return;
    _started = true;

    _notificationOpenedSubscription =
        _messaging.onNotificationOpened.listen(_handleNotificationOpened);

    final initialData = await _messaging.takeInitialNotificationData();
    if (initialData != null && initialData.isNotEmpty) {
      await _handleNotificationOpened(initialData);
    }
  }

  /// Refreshes the current OS permission without showing a prompt.
  Future<void> refreshPermissionStatus() async {
    final granted = await _messaging.hasPermission();
    emit(state.copyWith(
      status: granted && state.isPermissionDenied
          ? NotificationsStatus.initial
          : state.status,
      permissionGranted: granted,
    ));
  }

  /// Call after login when we only want to register silently if permission was
  /// already granted. This avoids surprising permission prompts at login.
  Future<void> syncTokenIfPermissionGranted() async {
    final granted = await _messaging.hasPermission();
    emit(state.copyWith(permissionGranted: granted));

    if (!granted) {
      return;
    }

    await _syncTokenAfterPermission();
  }

  /// Called from the notification settings screen CTA: asks for permission,
  /// fetches the token, and registers it for the user.
  Future<void> syncToken() async {
    emit(state.copyWith(status: NotificationsStatus.syncing));

    final granted = await _messaging.requestPermission();
    if (!granted) {
      emit(state.copyWith(
        status: NotificationsStatus.permissionDenied,
        permissionGranted: false,
      ));
      return;
    }

    emit(state.copyWith(permissionGranted: true));
    await _syncTokenAfterPermission();
  }

  Future<void> _syncTokenAfterPermission() async {
    emit(state.copyWith(status: NotificationsStatus.syncing));

    final token = await _messaging.getToken();
    if (token == null || token.isEmpty) {
      emit(state.copyWith(
        status: NotificationsStatus.error,
        permissionGranted: true,
        errorMessage: 'Could not obtain a push token.',
      ));
      return;
    }

    await _sendToken(token);
  }

  /// Call on sign-out: removes this device's token from the backend and rotates
  /// the local token so the next user starts clean.
  Future<void> clearToken() async {
    final token = await _messaging.getToken();
    if (token != null && token.isNotEmpty) {
      await _unregisterDeviceToken(
        params: UnregisterDeviceTokenParams(token: token),
      );
    }

    await _messaging.deleteToken();
    emit(state.copyWith(status: NotificationsStatus.initial));
  }

  Future<void> _sendToken(String token) async {
    final result = await _registerDeviceToken(
      params: RegisterDeviceTokenParams(token: token, platform: _platform),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        status: NotificationsStatus.error,
        permissionGranted: true,
        errorMessage: failure.message,
      )),
      (_) => emit(state.copyWith(
        status: NotificationsStatus.synced,
        permissionGranted: true,
      )),
    );
  }

  Future<void> _handleNotificationOpened(Map<String, dynamic> data) async {
    await _trackFromPayload(data, 'open');
    await _trackFromPayload(data, 'click');
    _deepLinkRouter.open(data);
  }

  Future<void> _trackFromPayload(
    Map<String, dynamic> data,
    String event,
  ) async {
    final campaignId = data['campaign_id']?.toString();
    if (campaignId == null || campaignId.isEmpty) {
      return;
    }

    await _trackNotificationEvent(
      params: TrackNotificationEventParams(
        event: event,
        campaignId: campaignId,
        recipientId: data['recipient_id']?.toString(),
      ),
    );
  }

  String get _platform => Platform.isIOS ? 'ios' : 'android';

  @override
  Future<void> close() {
    _tokenRefreshSubscription?.cancel();
    _notificationOpenedSubscription?.cancel();
    return super.close();
  }
}

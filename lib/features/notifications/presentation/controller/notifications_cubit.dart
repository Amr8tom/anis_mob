import 'dart:async';
import 'dart:io';

import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/notifications/push_messaging_service.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';

part 'notifications_state.dart';

/// Long-lived coordinator for the device's push token. It owns the side-effect
/// chain "permission → fetch token → register with backend", keeps the backend
/// in sync when FCM rotates the token, and clears it on sign-out.
class NotificationsCubit extends Cubit<NotificationsState> {
  final PushMessagingService _messaging;
  final RegisterDeviceTokenUseCase _registerDeviceToken;
  final UnregisterDeviceTokenUseCase _unregisterDeviceToken;

  StreamSubscription<String>? _tokenRefreshSubscription;

  NotificationsCubit(
    this._messaging,
    this._registerDeviceToken,
    this._unregisterDeviceToken,
  ) : super(const NotificationsState()) {
    // Keep the backend in sync whenever FCM issues a new token for this device.
    _tokenRefreshSubscription = _messaging.onTokenRefresh.listen(_sendToken);
  }

  /// Call after a successful login (and on app start when already signed in):
  /// asks for permission, fetches the token, and registers it for the user.
  Future<void> syncToken() async {
    emit(state.copyWith(status: NotificationsStatus.syncing));

    final granted = await _messaging.requestPermission();
    if (!granted) {
      emit(state.copyWith(status: NotificationsStatus.permissionDenied));
      return;
    }

    final token = await _messaging.getToken();
    if (token == null || token.isEmpty) {
      emit(state.copyWith(
        status: NotificationsStatus.error,
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
        errorMessage: failure.message,
      )),
      (_) => emit(state.copyWith(status: NotificationsStatus.synced)),
    );
  }

  String get _platform => Platform.isIOS ? 'ios' : 'android';

  @override
  Future<void> close() {
    _tokenRefreshSubscription?.cancel();
    return super.close();
  }
}

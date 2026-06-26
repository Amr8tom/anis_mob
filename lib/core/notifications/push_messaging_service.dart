import 'dart:async';
import 'dart:convert';

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

/// App-wide infrastructure that wraps Firebase Cloud Messaging and the local
/// notifications plugin. It owns the raw platform/SDK plumbing only — it knows
/// nothing about the backend API, repositories, or UI. Feature code talks to it
/// through this abstraction (token lifecycle + foreground display).
abstract class PushMessagingService {
  /// Wires the foreground message listener and the local-notifications channel.
  /// Safe to call once at app start.
  Future<void> init();

  /// Emits FCM data payloads when the user opens/taps a notification.
  Stream<Map<String, dynamic>> get onNotificationOpened;

  /// Returns the payload that opened the app from a terminated state once.
  Future<Map<String, dynamic>?> takeInitialNotificationData();

  /// Asks the OS for notification permission (required on iOS and Android 13+).
  /// Returns whether the user authorized notifications.
  Future<bool> requestPermission();

  /// Returns the current OS notification permission without showing a prompt.
  Future<bool> hasPermission();

  /// The current FCM registration token for this device, or `null` if none is
  /// available yet (e.g. permission denied or no Play Services).
  Future<String?> getToken();

  /// Emits a new token whenever FCM rotates it.
  Stream<String> get onTokenRefresh;

  /// Drops the current token from the device (used on sign-out so the device
  /// gets a fresh token for the next user).
  Future<void> deleteToken();
}

/// Top-level background/terminated message handler. Must be a top-level (or
/// static) function and annotated as an entry point so the framework can call
/// it in a separate isolate. Display of background messages is handled by the
/// OS from the FCM `notification` payload; this hook is only for data work.
@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  // Intentionally no-op for now: notification payloads are rendered by the OS.
  // Data-only background processing can be added here later if needed.
}

class FirebasePushMessagingService implements PushMessagingService {
  FirebasePushMessagingService(this._messaging, this._localNotifications);

  final FirebaseMessaging _messaging;
  final FlutterLocalNotificationsPlugin _localNotifications;
  final StreamController<Map<String, dynamic>> _openedController =
      StreamController<Map<String, dynamic>>.broadcast();
  Map<String, dynamic>? _initialNotificationData;

  static const AndroidNotificationChannel _channel = AndroidNotificationChannel(
    'anis_high_importance_channel',
    'Anis Notifications',
    description: 'General notifications from Anis.',
    importance: Importance.high,
  );

  @override
  Future<void> init() async {
    await _initLocalNotifications();

    // iOS shows foreground notifications natively when these options are on;
    // on Android we render them manually via local notifications below.
    await _messaging.setForegroundNotificationPresentationOptions(
      alert: true,
      badge: true,
      sound: true,
    );

    FirebaseMessaging.onMessage.listen(_showForeground);
    FirebaseMessaging.onMessageOpenedApp.listen(_emitOpenedMessage);

    final initialMessage = await _messaging.getInitialMessage();
    if (initialMessage != null) {
      _initialNotificationData = Map<String, dynamic>.from(
        initialMessage.data,
      );
    }
  }

  @override
  Future<bool> requestPermission() async {
    final settings = await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    return settings.authorizationStatus == AuthorizationStatus.authorized ||
        settings.authorizationStatus == AuthorizationStatus.provisional;
  }

  @override
  Future<bool> hasPermission() async {
    final settings = await _messaging.getNotificationSettings();

    return settings.authorizationStatus == AuthorizationStatus.authorized ||
        settings.authorizationStatus == AuthorizationStatus.provisional;
  }

  @override
  Future<String?> getToken() => _messaging.getToken();

  @override
  Stream<String> get onTokenRefresh => _messaging.onTokenRefresh;

  @override
  Stream<Map<String, dynamic>> get onNotificationOpened =>
      _openedController.stream;

  @override
  Future<Map<String, dynamic>?> takeInitialNotificationData() async {
    final data = _initialNotificationData;
    _initialNotificationData = null;
    return data;
  }

  @override
  Future<void> deleteToken() => _messaging.deleteToken();

  Future<void> _initLocalNotifications() async {
    const androidSettings = AndroidInitializationSettings(
      '@mipmap/ic_launcher',
    );
    const iosSettings = DarwinInitializationSettings();
    const settings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      settings,
      onDidReceiveNotificationResponse: _emitLocalNotificationResponse,
    );

    await _localNotifications
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(_channel);
  }

  void _showForeground(RemoteMessage message) {
    final notification = message.notification;
    if (notification == null) {
      return;
    }

    _localNotifications.show(
      notification.hashCode,
      notification.title,
      notification.body,
      NotificationDetails(
        android: AndroidNotificationDetails(
          _channel.id,
          _channel.name,
          channelDescription: _channel.description,
          importance: Importance.high,
          priority: Priority.high,
          icon: '@mipmap/ic_launcher',
        ),
        iOS: const DarwinNotificationDetails(),
      ),
      payload: jsonEncode(message.data),
    );
  }

  void _emitOpenedMessage(RemoteMessage message) {
    _openedController.add(Map<String, dynamic>.from(message.data));
  }

  void _emitLocalNotificationResponse(NotificationResponse response) {
    final payload = response.payload;
    if (payload == null || payload.isEmpty) return;

    try {
      final decoded = jsonDecode(payload);
      if (decoded is Map<String, dynamic>) {
        _openedController.add(decoded);
      } else if (decoded is Map) {
        _openedController.add(Map<String, dynamic>.from(decoded));
      }
    } catch (_) {
      // Ignore malformed local notification payloads. The notification itself
      // already displayed; analytics/deep-linking should fail quietly.
    }
  }
}

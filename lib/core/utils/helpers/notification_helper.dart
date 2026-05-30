// import 'dart:convert';
// import 'dart:io';
// import 'package:firebase_messaging/firebase_messaging.dart';
// import 'package:flutter/foundation.dart';
// import 'package:flutter/material.dart';
// import 'package:flutter_local_notifications/flutter_local_notifications.dart';
// import 'package:permission_handler/permission_handler.dart';
//
// class NotificationHelper {
//   static final NotificationHelper _instance = NotificationHelper._internal();
//   factory NotificationHelper() => _instance;
//   NotificationHelper._internal();
//
//   static const String _channelId = 'teaa_notifications';
//   static const String _channelName = 'TEAA Notifications';
//   static const String _channelDescription = 'TEAA app notifications';
//
//   FirebaseMessaging? _firebaseMessaging;
//   FlutterLocalNotificationsPlugin? _localNotifications;
//   String? _fcmToken;
//
//   /// Initialize notification services
//   Future<void> initialize() async {
//     try {
//       _firebaseMessaging = FirebaseMessaging.instance;
//       _localNotifications = FlutterLocalNotificationsPlugin();
//
//       await _initializeLocalNotifications();
//       await _initializeFirebaseMessaging();
//       await _requestPermissions();
//       await _setupMessageHandlers();
//
//       debugPrint('✅ NotificationHelper initialized successfully');
//     } catch (e) {
//       debugPrint('❌ NotificationHelper initialization failed: $e');
//     }
//   }
//
//   /// Initialize local notifications
//   Future<void> _initializeLocalNotifications() async {
//     const AndroidInitializationSettings androidSettings =
//     AndroidInitializationSettings('@mipmap/ic_launcher');
//
//     const DarwinInitializationSettings iosSettings =
//     DarwinInitializationSettings(
//       requestAlertPermission: true,
//       requestBadgePermission: true,
//       requestSoundPermission: true,
//     );
//
//     const InitializationSettings settings = InitializationSettings(
//       android: androidSettings,
//       iOS: iosSettings,
//     );
//
//     await _localNotifications?.initialize(
//       settings,
//       onDidReceiveNotificationResponse: _onNotificationTapped,
//     );
//
//     // Create notification channel for Android
//     if (Platform.isAndroid) {
//       await _createNotificationChannel();
//     }
//   }
//
//   /// Create notification channel for Android
//   Future<void> _createNotificationChannel() async {
//     const AndroidNotificationChannel channel = AndroidNotificationChannel(
//       _channelId,
//       _channelName,
//       description: _channelDescription,
//       importance: Importance.high,
//       showBadge: true,
//       playSound: true,
//     );
//
//     await _localNotifications
//         ?.resolvePlatformSpecificImplementation<
//         AndroidFlutterLocalNotificationsPlugin>()
//         ?.createNotificationChannel(channel);
//   }
//
//   /// Initialize Firebase Messaging
//   Future<void> _initializeFirebaseMessaging() async {
//     // Get FCM token
//     _fcmToken = await _firebaseMessaging?.getToken();
//     debugPrint('🔑 FCM Token: $_fcmToken');
//
//     // Listen to token refresh
//     _firebaseMessaging?.onTokenRefresh.listen((newToken) {
//       _fcmToken = newToken;
//       debugPrint('🔄 FCM Token refreshed: $newToken');
//       // TODO: Send token to your backend server
//       _sendTokenToServer(newToken);
//     });
//   }
//
//   /// Request notification permissions
//   Future<bool> _requestPermissions() async {
//     if (Platform.isIOS) {
//       final NotificationSettings settings =
//       await _firebaseMessaging!.requestPermission(
//         alert: true,
//         badge: true,
//         sound: true,
//         carPlay: false,
//         criticalAlert: false,
//         provisional: false,
//         announcement: false,
//       );
//
//       debugPrint('📱 iOS Notification permission: ${settings.authorizationStatus}');
//       return settings.authorizationStatus == AuthorizationStatus.authorized;
//     } else {
//       final PermissionStatus status = await Permission.notification.request();
//       debugPrint('📱 Android Notification permission: $status');
//       return status.isGranted;
//     }
//   }
//
//   /// Setup message handlers
//   Future<void> _setupMessageHandlers() async {
//     // Handle foreground messages
//     FirebaseMessaging.onMessage.listen(_handleForegroundMessage);
//
//     // Handle background messages
//     FirebaseMessaging.onBackgroundMessage(_handleBackgroundMessage);
//
//     // Handle notification taps when app is in background/terminated
//     FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);
//
//     // Handle initial message if app was opened from notification
//     final RemoteMessage? initialMessage =
//     await _firebaseMessaging?.getInitialMessage();
//     if (initialMessage != null) {
//       _handleNotificationTap(initialMessage);
//     }
//   }
//
//   /// Handle foreground messages
//   Future<void> _handleForegroundMessage(RemoteMessage message) async {
//     debugPrint('📥 Foreground message: ${message.notification?.title}');
//
//     // Show local notification when app is in foreground
//     await _showLocalNotification(
//       title: message.notification?.title ?? 'TEAA',
//       body: message.notification?.body ?? '',
//       data: message.data,
//     );
//   }
//
//   /// Handle notification tap
//   void _handleNotificationTap(RemoteMessage message) {
//     debugPrint('🔔 Notification tapped: ${message.data}');
//
//     // Navigate based on notification data
//     _navigateBasedOnData(message.data);
//   }
//
//   /// Handle notification response from local notifications
//   void _onNotificationTapped(NotificationResponse response) {
//     debugPrint('🔔 Local notification tapped: ${response.payload}');
//
//     if (response.payload != null) {
//       final Map<String, dynamic> data = jsonDecode(response.payload!);
//       _navigateBasedOnData(data);
//     }
//   }
//
//   /// Navigate based on notification data
//   void _navigateBasedOnData(Map<String, dynamic> data) {
//     final String? type = data['type'];
//     final String? id = data['id'];
//
//     switch (type) {
//       case 'order':
//       // Navigate to order details
//       // NavigationService.navigateTo('/order/$id');
//         break;
//       case 'chat':
//       // Navigate to chat
//       // NavigationService.navigateTo('/chat/$id');
//         break;
//       case 'promotion':
//       // Navigate to promotions
//       // NavigationService.navigateTo('/promotions');
//         break;
//       default:
//       // Navigate to home
//       // NavigationService.navigateTo('/home');
//         break;
//     }
//   }
//
//   /// Show local notification
//   Future<void> _showLocalNotification({
//     required String title,
//     required String body,
//     Map<String, dynamic>? data,
//   }) async {
//     const AndroidNotificationDetails androidDetails =
//     AndroidNotificationDetails(
//       _channelId,
//       _channelName,
//       channelDescription: _channelDescription,
//       importance: Importance.high,
//       priority: Priority.high,
//       showWhen: true,
//       icon: '@mipmap/ic_launcher',
//     );
//
//     const DarwinNotificationDetails iosDetails = DarwinNotificationDetails(
//       presentAlert: true,
//       presentBadge: true,
//       presentSound: true,
//     );
//
//     const NotificationDetails details = NotificationDetails(
//       android: androidDetails,
//       iOS: iosDetails,
//     );
//
//     await _localNotifications?.show(
//       DateTime.now().millisecondsSinceEpoch.remainder(100000),
//       title,
//       body,
//       details,
//       payload: data != null ? jsonEncode(data) : null,
//     );
//   }
//
//   /// Send FCM token to server
//   Future<void> _sendTokenToServer(String token) async {
//     try {
//       // TODO: Implement your API call to send token to backend
//       debugPrint('📤 Sending FCM token to server: $token');
//
//       // Example API call:
//       // await ApiService.sendFCMToken(token);
//     } catch (e) {
//       debugPrint('❌ Failed to send FCM token to server: $e');
//     }
//   }
//
//   /// Get current FCM token
//   String? get fcmToken => _fcmToken;
//
//   /// Subscribe to topic
//   Future<void> subscribeToTopic(String topic) async {
//     try {
//       await _firebaseMessaging?.subscribeToTopic(topic);
//       debugPrint('✅ Subscribed to topic: $topic');
//     } catch (e) {
//       debugPrint('❌ Failed to subscribe to topic $topic: $e');
//     }
//   }
//
//   /// Unsubscribe from topic
//   Future<void> unsubscribeFromTopic(String topic) async {
//     try {
//       await _firebaseMessaging?.unsubscribeFromTopic(topic);
//       debugPrint('✅ Unsubscribed from topic: $topic');
//     } catch (e) {
//       debugPrint('❌ Failed to unsubscribe from topic $topic: $e');
//     }
//   }
//
//   /// Clear all notifications
//   Future<void> clearAllNotifications() async {
//     await _localNotifications?.cancelAll();
//   }
//
//   /// Get notification count
//   Future<int> getNotificationCount() async {
//     final List<PendingNotificationRequest> pending =
//         await _localNotifications?.pendingNotificationRequests() ?? [];
//     return pending.length;
//   }
// }
//
// /// Background message handler (must be top-level function)
// @pragma('vm:entry-point')
// Future<void> _handleBackgroundMessage(RemoteMessage message) async {
//   debugPrint('📥 Background message: ${message.notification?.title}');
//   // Handle background message logic here
// }
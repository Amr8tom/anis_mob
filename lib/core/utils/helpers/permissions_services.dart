import 'dart:io';
import 'package:app_tracking_transparency/app_tracking_transparency.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:permission_handler/permission_handler.dart';

class PermissionsService {
  PermissionsService._();

  static final PermissionsService instance = PermissionsService._();

  factory PermissionsService() => instance;

  static Future<void> requestPermission(
    Permission permission,
    String permissionName,
  ) async {
    PermissionStatus status = await permission.status;
    if (status.isDenied || status.isPermanentlyDenied) {
      status = await permission.request();
    }
  }

  static Future<bool> checkPermission(Permission permission) async {
    PermissionStatus status = await permission.status;
    return status.isGranted;
  }

  static Future<void> notifications() async {
    if (Platform.isIOS) {
      PermissionStatus status = await Permission.notification.status;

      if (status.isPermanentlyDenied || status.isDenied) {
        await requestPermission(Permission.notification, 'notification');

        await IOSFlutterLocalNotificationsPlugin().requestPermissions(
          alert: true,
          badge: true,
          sound: true,
        );

        // await openAppSettings();
      }
      if (status.isPermanentlyDenied) {
        await IOSFlutterLocalNotificationsPlugin().requestPermissions(
          alert: true,
          badge: true,
          sound: true,
        );
      }
    } else {
      await requestPermission(Permission.notification, 'Notification');
    }
  }

  static Future<void> alarm() async {
    await requestPermission(Permission.scheduleExactAlarm, 'Exact alarm');
  }

  // static Future<void> location() async {
  //   if (Platform.isIOS) {
  //     await Geolocator.requestPermission();
  //   } else {
  //     await requestPermission(Permission.location, 'Location');
  //     await Geolocator.requestPermission();
  //   }
  // }

  static Future<void> activityRecognition() async {
    await requestPermission(
      Permission.activityRecognition,
      'Activity Recognition',
    );
  }

  static Future<void> microphone() async {
    await requestPermission(Permission.microphone, 'Microphone');
  }

  static Future<void> camera() async {
    await requestPermission(Permission.camera, 'Camera');
  }

  static Future<void> storage() async {
    await requestPermission(Permission.storage, 'Storage');
  }

  static Future<void> phone() async {
    await requestPermission(Permission.phone, 'Phone');
  }

  static Future<void> contacts() async {
    await requestPermission(Permission.contacts, 'Contacts');
  }

  static Future<void> calendar() async {
    await requestPermission(Permission.calendarFullAccess, 'Calendar');
  }

  static Future<void> photos() async {
    await requestPermission(Permission.photos, 'Photos');
  }

  static Future<void> network() async {
    await requestPermission(Permission.accessMediaLocation, 'Network');
  }

  static Future<void> appTracking() async {
    if (Platform.isIOS) {
      try {
        final status =
            await AppTrackingTransparency.requestTrackingAuthorization();
        switch (status) {
          case TrackingStatus.authorized:
            break;
          case TrackingStatus.denied:
            break;
          case TrackingStatus.notDetermined:
            break;
          case TrackingStatus.restricted:
            break;
          case TrackingStatus.notSupported:
            break;
        }
      } catch (_) {}
    }
  }

  static Future<void> tracking() async {
    if (Platform.isAndroid) {
      await activityRecognition();
    } else if (Platform.isIOS) {
      await appTracking();
    }
  }
}

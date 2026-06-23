import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../use_cases/register_device_token_use_case.dart';
import '../use_cases/unregister_device_token_use_case.dart';

/// Contract for syncing the current device's push token with the backend.
/// Both operations are online-only writes (no offline queue is required).
abstract class NotificationsRepository {
  Future<Either<Failure, Unit>> registerDeviceToken({
    required RegisterDeviceTokenParams params,
  });

  Future<Either<Failure, Unit>> unregisterDeviceToken({
    required UnregisterDeviceTokenParams params,
  });
}

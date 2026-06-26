import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entities/notification_preferences_entity.dart';
import '../../domain/repositories/notifications_repository.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/track_notification_event_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';
import '../../domain/use_cases/update_notification_preferences_use_case.dart';
import '../data_sources/notifications_local_data_source.dart';
import '../data_sources/notifications_remote_data_source.dart';
import '../models/notification_preferences_model.dart';

class NotificationsRepositoryImpl implements NotificationsRepository {
  final NotificationsRemoteDataSource _remoteDataSource;
  final NotificationsLocalDataSource _localDataSource;
  final NetworkInfo _networkInfo;

  const NotificationsRepositoryImpl(
    this._remoteDataSource,
    this._localDataSource,
    this._networkInfo,
  );

  @override
  Future<Either<Failure, Unit>> registerDeviceToken({
    required RegisterDeviceTokenParams params,
  }) async {
    // Token registration is an online-only write; offline it simply no-ops
    // and is retried on the next sync (login / app start / token refresh).
    if (!await _networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      await _remoteDataSource.registerDeviceToken(params);
      return const Right(unit);
    } on Failure catch (failure) {
      return Left(failure);
    }
  }

  @override
  Future<Either<Failure, Unit>> unregisterDeviceToken({
    required UnregisterDeviceTokenParams params,
  }) async {
    if (!await _networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      await _remoteDataSource.unregisterDeviceToken(params);
      return const Right(unit);
    } on Failure catch (failure) {
      return Left(failure);
    }
  }

  @override
  Future<Either<Failure, NotificationPreferencesEntity>>
      getNotificationPreferences() async {
    if (await _networkInfo.isConnected) {
      try {
        final preferences =
            await _remoteDataSource.getNotificationPreferences();
        await _localDataSource.cachePreferences(preferences);
        return Right(preferences);
      } on Failure catch (failure) {
        try {
          return Right(await _localDataSource.getCachedPreferences());
        } on Failure {
          return Left(failure);
        }
      }
    }

    try {
      return Right(await _localDataSource.getCachedPreferences());
    } on Failure catch (failure) {
      return Left(failure);
    }
  }

  @override
  Future<Either<Failure, NotificationPreferencesEntity>>
      updateNotificationPreferences({
    required UpdateNotificationPreferencesParams params,
  }) async {
    if (!await _networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      final preferences =
          await _remoteDataSource.updateNotificationPreferences(params);
      await _localDataSource.cachePreferences(
        NotificationPreferencesModel.fromEntity(preferences),
      );
      return Right(preferences);
    } on Failure catch (failure) {
      return Left(failure);
    }
  }

  @override
  Future<Either<Failure, Unit>> trackNotificationEvent({
    required TrackNotificationEventParams params,
  }) async {
    if (!await _networkInfo.isConnected) {
      return const Left(NetworkFailure());
    }

    try {
      await _remoteDataSource.trackNotificationEvent(params);
      return const Right(unit);
    } on Failure catch (failure) {
      return Left(failure);
    }
  }
}

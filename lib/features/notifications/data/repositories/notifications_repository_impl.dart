import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
import '../../../../core/error/failure.dart';
import '../../domain/repositories/notifications_repository.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';
import '../data_sources/notifications_remote_data_source.dart';

class NotificationsRepositoryImpl implements NotificationsRepository {
  final NotificationsRemoteDataSource _remoteDataSource;
  final NetworkInfo _networkInfo;

  const NotificationsRepositoryImpl(this._remoteDataSource, this._networkInfo);

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
}

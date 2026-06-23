import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../repositories/notifications_repository.dart';

/// Removes the current device's FCM token from the backend (call on sign-out)
/// so the user stops receiving push on a device they logged out of.
class UnregisterDeviceTokenUseCase
    extends UseCase<Unit, UnregisterDeviceTokenParams> {
  final NotificationsRepository repository;

  UnregisterDeviceTokenUseCase(this.repository);

  @override
  Future<Either<Failure, Unit>> call({
    required UnregisterDeviceTokenParams params,
  }) {
    return repository.unregisterDeviceToken(params: params);
  }
}

class UnregisterDeviceTokenParams {
  final String token;

  const UnregisterDeviceTokenParams({required this.token});

  Map<String, dynamic> toMap() {
    return {'token': token};
  }
}

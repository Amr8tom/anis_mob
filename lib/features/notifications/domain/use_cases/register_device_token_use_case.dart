import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../repositories/notifications_repository.dart';

/// Registers (or refreshes) the current device's FCM token for the signed-in
/// user. Idempotent on the backend: re-binds an existing token instead of
/// duplicating it.
class RegisterDeviceTokenUseCase
    extends UseCase<Unit, RegisterDeviceTokenParams> {
  final NotificationsRepository repository;

  RegisterDeviceTokenUseCase(this.repository);

  @override
  Future<Either<Failure, Unit>> call({
    required RegisterDeviceTokenParams params,
  }) {
    return repository.registerDeviceToken(params: params);
  }
}

class RegisterDeviceTokenParams {
  final String token;
  final String platform; // android | ios

  const RegisterDeviceTokenParams({
    required this.token,
    required this.platform,
  });

  Map<String, dynamic> toMap() {
    return {
      'token': token,
      'platform': platform,
    };
  }
}

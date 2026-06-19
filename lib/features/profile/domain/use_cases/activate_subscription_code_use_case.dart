import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/subscription_activation_entity.dart';
import '../repository/profile_repository.dart';

class ActivateSubscriptionCodeUseCase {
  final ProfileRepository repository;

  const ActivateSubscriptionCodeUseCase(this.repository);

  Future<Either<Failure, SubscriptionActivationEntity>> call(String code) {
    return repository.activateSubscriptionCode(code);
  }
}

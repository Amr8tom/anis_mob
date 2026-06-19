import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/profile_entity.dart';
import '../entity/subscription_activation_entity.dart';
import '../use_cases/update_profile_use_case.dart';

abstract class ProfileRepository {
  Future<Either<Failure, ProfileEntity>> getProfile();
  Future<Either<Failure, ProfileEntity>> updateProfile(
    UpdateProfileParams params,
  );
  Future<Either<Failure, SubscriptionActivationEntity>>
      activateSubscriptionCode(
    String code,
  );
}

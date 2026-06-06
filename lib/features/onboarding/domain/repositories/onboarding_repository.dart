import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';

abstract class OnboardingRepository {
  Future<Either<Failure, bool>> hasCompletedOnboarding();

  Future<Either<Failure, void>> completeOnboarding();
}

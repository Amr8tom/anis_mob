import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/onboarding_repository.dart';

class GetOnboardingStatusUseCase {
  final OnboardingRepository repository;

  const GetOnboardingStatusUseCase(this.repository);

  Future<Either<Failure, bool>> call() => repository.hasCompletedOnboarding();
}

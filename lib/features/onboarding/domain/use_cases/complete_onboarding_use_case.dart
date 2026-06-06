import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/onboarding_repository.dart';

class CompleteOnboardingUseCase {
  final OnboardingRepository repository;

  const CompleteOnboardingUseCase(this.repository);

  Future<Either<Failure, void>> call() => repository.completeOnboarding();
}

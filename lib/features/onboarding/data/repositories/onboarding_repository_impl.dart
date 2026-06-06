import 'package:dartz/dartz.dart';
import '../../../../core/error/failure.dart';
import '../../domain/repositories/onboarding_repository.dart';
import '../data_sources/onboarding_local_data_source.dart';

class OnboardingRepositoryImpl implements OnboardingRepository {
  final OnboardingLocalDataSource localDataSource;
  const OnboardingRepositoryImpl(this.localDataSource);
  @override
  Future<Either<Failure, bool>> hasCompletedOnboarding() async {
    try {
      return Right(await localDataSource.hasCompletedOnboarding());
    } catch (_) {
      return Left(CacheFailure());
    }
  }
  @override
  Future<Either<Failure, void>> completeOnboarding() async {
    try {
      await localDataSource.completeOnboarding();
      return const Right(null);
    } catch (_) {
      return Left(CacheFailure());
    }
  }
}
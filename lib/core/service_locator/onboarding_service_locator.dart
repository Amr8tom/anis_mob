import 'package:get_it/get_it.dart';

import '../../features/onboarding/data/data_sources/onboarding_local_data_source.dart';
import '../../features/onboarding/data/repositories/onboarding_repository_impl.dart';
import '../../features/onboarding/domain/repositories/onboarding_repository.dart';
import '../../features/onboarding/domain/use_cases/complete_onboarding_use_case.dart';
import '../../features/onboarding/domain/use_cases/get_onboarding_status_use_case.dart';
import '../../features/onboarding/presentation/controller/onboarding_cubit.dart';

class OnboardingServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<OnboardingLocalDataSource>(
      () => OnboardingLocalDataSourceImpl(serviceLocator()),
    );

    serviceLocator.registerLazySingleton<OnboardingRepository>(
      () => OnboardingRepositoryImpl(serviceLocator()),
    );

    serviceLocator.registerLazySingleton(
      () => GetOnboardingStatusUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => CompleteOnboardingUseCase(serviceLocator()),
    );

    serviceLocator.registerFactory(
      () => OnboardingCubit(serviceLocator()),
    );
  }
}

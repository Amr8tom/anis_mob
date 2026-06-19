import 'package:get_it/get_it.dart';

import '../../features/profile/data/data_sources/profile_local_data_source.dart';
import '../../features/profile/data/data_sources/profile_remote_data_source.dart';
import '../../features/profile/data/repositories/profile_repository_impl.dart';
import '../../features/profile/domain/repository/profile_repository.dart';
import '../../features/profile/domain/use_cases/get_profile_use_case.dart';
import '../../features/profile/domain/use_cases/update_profile_use_case.dart';
import '../../features/profile/domain/use_cases/activate_subscription_code_use_case.dart';
import '../../features/profile/presentation/controller/profile_completion_cubit.dart';
import '../../features/profile/presentation/controller/profile_cubit.dart';

class ProfileServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<ProfileRemoteDataSource>(
      () => ProfileRemoteDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<ProfileLocalDataSource>(
      () => ProfileLocalDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<ProfileRepository>(
      () => ProfileRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<GetProfileUseCase>(
      () => GetProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<UpdateProfileUseCase>(
      () => UpdateProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<ActivateSubscriptionCodeUseCase>(
      () => ActivateSubscriptionCodeUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<ProfileCubit>(
      () => ProfileCubit(
        getProfileUseCase: serviceLocator(),
        updateProfileUseCase: serviceLocator(),
        getGuestStatusUseCase: serviceLocator(),
      ),
    );
    serviceLocator.registerFactory<ProfileCompletionCubit>(
      () => ProfileCompletionCubit(
        getProfileUseCase: serviceLocator(),
        updateProfileUseCase: serviceLocator(),
      ),
    );
  }
}

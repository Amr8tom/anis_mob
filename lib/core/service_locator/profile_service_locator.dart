import 'package:get_it/get_it.dart';

import '../../features/profile/data/data_sources/profile_local_data_source.dart';
import '../../features/profile/data/data_sources/profile_remote_data_source.dart';
import '../../features/profile/data/repositories/profile_dummy_repository.dart';
import '../../features/profile/data/repositories/profile_repository_impl.dart';
import '../../features/profile/domain/repository/profile_repository.dart';
import '../../features/profile/domain/use_cases/get_profile_use_case.dart';
import '../../features/profile/presentation/controller/profile_cubit.dart';

class ProfileServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<ProfileRemoteDataSource>(
      () => ProfileRemoteDataSourceImpl(),
    );
    serviceLocator.registerLazySingleton<ProfileLocalDataSource>(
      () => ProfileLocalDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<ProfileRepositoryImpl>(
      () => ProfileRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<ProfileRepository>(
      () => ProfileDummyRepository(),
    );
    serviceLocator.registerLazySingleton<GetProfileUseCase>(
      () => GetProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<ProfileCubit>(
      () => ProfileCubit(getProfileUseCase: serviceLocator()),
    );
  }
}

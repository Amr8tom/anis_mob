import 'package:get_it/get_it.dart';

import '../../feature/profile/data/data_sources/profile_remote_data_source.dart';
import '../../feature/profile/data/repositories/profile_repository_impl.dart';
import '../../feature/profile/domain/repository/profile_repository.dart';
import '../../feature/profile/domain/use_cases/get_profile_use_case.dart';
import '../../feature/profile/presentation/controller/profile_cubit.dart';

class ProfileServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<ProfileRemoteDataSource>(
      () => ProfileRemoteDataSourceImpl(),
    );
    serviceLocator.registerLazySingleton<ProfileRepository>(
      () => ProfileRepositoryImpl(remoteDataSource: serviceLocator()),
    );
    serviceLocator.registerLazySingleton<GetProfileUseCase>(
      () => GetProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<ProfileCubit>(
      () => ProfileCubit(getProfileUseCase: serviceLocator()),
    );
  }
}

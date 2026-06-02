import 'package:get_it/get_it.dart';

import '../../feature/auth/data/data_sources/local_data_sources.dart';
import '../../feature/auth/data/data_sources/remote_data_sources.dart';
import '../../feature/auth/data/repositories/repository.dart';
import '../../feature/auth/domain/repositories/auth_repository.dart';
import '../../feature/auth/domain/use_cases/create_user_use_case.dart';
import '../../feature/auth/domain/use_cases/login_user_use_case.dart';
import '../../feature/auth/presentation/controller/login/login_cubit.dart';
import '../../feature/auth/presentation/controller/user_info/user_info_cubit.dart';

class AuthServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Data sources ──────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<AuthRemoteDataSources>(
      () => AuthRemoteDataSourcesImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<AuthLocalDataSources>(
      () => AuthLocalDataSourcesImpl(),
    );

    // ── Repository ────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<AuthRepository>(
      () => AuthRepositoryImp(serviceLocator(), serviceLocator(),serviceLocator()),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton(
      () => LoginUserUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => CreateUserUseCase(serviceLocator()),
    );

    // ── Cubits ────────────────────────────────────────────────────────────────
    serviceLocator.registerFactory(
      () => LoginCubit(serviceLocator()),
    );
    serviceLocator.registerFactory(
      () => UserInfoCubit(serviceLocator()),
    );
  }
}

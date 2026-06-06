import 'package:get_it/get_it.dart';

import '../../features/auth/data/data_sources/local_data_sources.dart';
import '../../features/auth/data/data_sources/remote_data_sources.dart';
import '../../features/auth/data/repositories/repository.dart';
import '../../features/auth/domain/repositories/auth_repository.dart';
import '../../features/auth/domain/use_cases/cache_user_info_draft_use_case.dart';
import '../../features/auth/domain/use_cases/create_user_use_case.dart';
import '../../features/auth/domain/use_cases/get_auth_token_use_case.dart';
import '../../features/auth/domain/use_cases/get_guest_status_use_case.dart';
import '../../features/auth/domain/use_cases/guest_login_use_case.dart';
import '../../features/auth/domain/use_cases/login_user_use_case.dart';
import '../../features/auth/domain/use_cases/sign_out_use_case.dart';
import '../../features/auth/presentation/controller/login/login_cubit.dart';
import '../../features/auth/presentation/controller/user_info/user_info_cubit.dart';

class AuthServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Data sources ──────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<AuthRemoteDataSources>(
      () => AuthRemoteDataSourcesImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<AuthLocalDataSources>(
      () => AuthLocalDataSourcesImpl(serviceLocator()),
    );

    // ── Repository ────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<AuthRepository>(
      () => AuthRepositoryImp(
        serviceLocator(),
        serviceLocator(),
        serviceLocator(),
      ),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton(
      () => LoginUserUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => CreateUserUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => GuestLoginUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => GetAuthTokenUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => GetGuestStatusUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => SignOutUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => CacheUserInfoDraftUseCase(serviceLocator()),
    );

    // ── Cubits (factories — new instance per screen) ──────────────────────────
    serviceLocator.registerFactory(
      () => LoginCubit(serviceLocator(), serviceLocator()),
    );
    serviceLocator.registerFactory(
      () => UserInfoCubit(serviceLocator(), serviceLocator()),
    );
  }
}

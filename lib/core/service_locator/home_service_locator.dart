import 'package:get_it/get_it.dart';

import '../../feature/home/data/data_sources/home_remote_data_source.dart';
import '../../feature/home/data/repositories/home_repository_impl.dart';
import '../../feature/home/domain/repository/home_repository.dart';
import '../../feature/home/domain/use_cases/get_today_sessions_use_case.dart';
import '../../feature/home/domain/use_cases/get_user_profile_use_case.dart';
import '../../feature/home/presentation/controller/home_cubit.dart';

class HomeServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Data sources ──────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<HomeRemoteDataSource>(
      () => HomeRemoteDataSourceImpl(
        // TODO: pass serviceLocator<DioHelper>() when switching to real API
      ),
    );

    // ── Repository ────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<HomeRepository>(
      () => HomeRepositoryImpl(remoteDataSource: serviceLocator()),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<GetUserProfileUseCase>(
      () => GetUserProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<GetTodaySessionsUseCase>(
      () => GetTodaySessionsUseCase(serviceLocator()),
    );

    // ── Cubit  (Factory → new instance per route) ─────────────────────────────
    serviceLocator.registerFactory<HomeCubit>(
      () => HomeCubit(
        getUserProfileUseCase: serviceLocator(),
        getTodaySessionsUseCase: serviceLocator(),
      ),
    );
  }
}

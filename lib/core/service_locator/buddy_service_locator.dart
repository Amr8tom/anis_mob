import 'package:get_it/get_it.dart';

import '../../features/buddy/data/data_sources/buddy_local_data_source.dart';
import '../../features/buddy/data/data_sources/buddy_remote_data_source.dart';
import '../../features/buddy/data/repositories/buddy_repository_impl.dart';
import '../../features/buddy/domain/repository/buddy_repository.dart';
import '../../features/buddy/domain/use_cases/create_buddy_session_use_case.dart';
import '../../features/buddy/domain/use_cases/get_buddy_session_details_use_case.dart';
import '../../features/buddy/domain/use_cases/get_buddy_sessions_use_case.dart';
import '../../features/buddy/domain/use_cases/join_buddy_session_use_case.dart';
import '../../features/buddy/presentation/controller/buddy_cubit.dart';
import '../../features/buddy/presentation/controller/buddy_session_details_cubit.dart';
import '../../features/workspaces/domain/use_cases/get_workspaces_use_case.dart';

class BuddyServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Data ─────────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<BuddyRemoteDataSource>(
      () => BuddyRemoteDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<BuddyLocalDataSource>(
      () => BuddyLocalDataSourceImpl(serviceLocator()),
    );

    // ── Repository (real API; offline cache preserved) ─────────────────────────
    serviceLocator.registerLazySingleton<BuddyRepository>(
      () => BuddyRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<GetBuddySessionsUseCase>(
      () => GetBuddySessionsUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<GetBuddySessionDetailsUseCase>(
      () => GetBuddySessionDetailsUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<JoinBuddySessionUseCase>(
      () => JoinBuddySessionUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<CreateBuddySessionUseCase>(
      () => CreateBuddySessionUseCase(serviceLocator()),
    );
    // GetWorkspacesUseCase is already registered by WorkspacesServiceLocator —
    // BuddyCubit simply resolves it from the same GetIt container.

    // ── Cubit ─────────────────────────────────────────────────────────────────
    serviceLocator.registerFactory<BuddyCubit>(
      () => BuddyCubit(
        getBuddySessionsUseCase: serviceLocator(),
        joinBuddySessionUseCase: serviceLocator(),
        createBuddySessionUseCase: serviceLocator(),
        getWorkspacesUseCase: serviceLocator<GetWorkspacesUseCase>(),
        getGuestStatusUseCase: serviceLocator(),
      ),
    );
    serviceLocator.registerFactoryParam<BuddySessionDetailsCubit, String, void>(
      (sessionId, _) => BuddySessionDetailsCubit(
        getBuddySessionDetailsUseCase: serviceLocator(),
        sessionId: sessionId,
      ),
    );
  }
}

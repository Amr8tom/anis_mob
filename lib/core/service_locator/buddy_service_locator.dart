import 'package:get_it/get_it.dart';

import '../../feature/buddy/data/data_sources/buddy_remote_data_source.dart';
import '../../feature/buddy/data/repositories/buddy_repository_impl.dart';
import '../../feature/buddy/domain/repository/buddy_repository.dart';
import '../../feature/buddy/domain/use_cases/get_buddy_sessions_use_case.dart';
import '../../feature/buddy/presentation/controller/buddy_cubit.dart';
import '../../feature/workspaces/domain/use_cases/get_workspaces_use_case.dart';

class BuddyServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // ── Data ─────────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<BuddyRemoteDataSource>(
      () => BuddyRemoteDataSourceImpl(),
    );

    // ── Repository ────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<BuddyRepository>(
      () => BuddyRepositoryImpl(remoteDataSource: serviceLocator()),
    );

    // ── Use cases ─────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton<GetBuddySessionsUseCase>(
      () => GetBuddySessionsUseCase(serviceLocator()),
    );
    // GetWorkspacesUseCase is already registered by WorkspacesServiceLocator —
    // BuddyCubit simply resolves it from the same GetIt container.

    // ── Cubit ─────────────────────────────────────────────────────────────────
    serviceLocator.registerFactory<BuddyCubit>(
      () => BuddyCubit(
        getBuddySessionsUseCase: serviceLocator(),
        buddyRepository: serviceLocator(),
        getWorkspacesUseCase: serviceLocator<GetWorkspacesUseCase>(),
      ),
    );
  }
}

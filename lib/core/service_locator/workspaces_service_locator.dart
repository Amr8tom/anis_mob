import 'package:get_it/get_it.dart';

import '../../features/workspaces/data/data_sources/workspace_local_data_source.dart';
import '../../features/workspaces/data/data_sources/workspace_remote_data_source.dart';
import '../../features/workspaces/data/repositories/workspace_repository_impl.dart';
import '../../features/workspaces/domain/repository/workspace_repository.dart';
import '../../features/workspaces/domain/use_cases/get_workspace_details_use_case.dart';
import '../../features/workspaces/domain/use_cases/get_workspaces_use_case.dart';
import '../../features/workspaces/presentation/controller/workspace_cubit.dart';
import '../../features/workspaces/presentation/controller/workspace_details_cubit.dart';

class WorkspacesServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<WorkspaceRemoteDataSource>(
      () => WorkspaceRemoteDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<WorkspaceLocalDataSource>(
      () => WorkspaceLocalDataSourceImpl(serviceLocator()),
    );
    // Real repository now hits the Laravel API (offline cache preserved).
    serviceLocator.registerLazySingleton<WorkspaceRepository>(
      () => WorkspaceRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<GetWorkspacesUseCase>(
      () => GetWorkspacesUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<GetWorkspaceDetailsUseCase>(
      () => GetWorkspaceDetailsUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<WorkspaceCubit>(
      () => WorkspaceCubit(
        getWorkspacesUseCase: serviceLocator(),
        localStorage: serviceLocator(),
      ),
    );
    serviceLocator.registerFactoryParam<WorkspaceDetailsCubit, String, void>(
      (workspaceId, _) => WorkspaceDetailsCubit(
        getWorkspaceDetailsUseCase: serviceLocator(),
        workspaceId: workspaceId,
      ),
    );
  }
}

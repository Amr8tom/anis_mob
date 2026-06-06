import 'package:get_it/get_it.dart';

import '../../features/workspaces/data/data_sources/workspace_local_data_source.dart';
import '../../features/workspaces/data/data_sources/workspace_remote_data_source.dart';
import '../../features/workspaces/data/repositories/workspace_dummy_repository.dart';
import '../../features/workspaces/data/repositories/workspace_repository_impl.dart';
import '../../features/workspaces/domain/repository/workspace_repository.dart';
import '../../features/workspaces/domain/use_cases/get_workspaces_use_case.dart';
import '../../features/workspaces/presentation/controller/workspace_cubit.dart';

class WorkspacesServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<WorkspaceRemoteDataSource>(
      () => WorkspaceRemoteDataSourceImpl(),
    );
    serviceLocator.registerLazySingleton<WorkspaceLocalDataSource>(
      () => WorkspaceLocalDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<WorkspaceRepositoryImpl>(
      () => WorkspaceRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<WorkspaceRepository>(
      () => WorkspaceDummyRepository(),
    );
    serviceLocator.registerLazySingleton<GetWorkspacesUseCase>(
      () => GetWorkspacesUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<WorkspaceCubit>(
      () => WorkspaceCubit(getWorkspacesUseCase: serviceLocator()),
    );
  }
}

import 'package:get_it/get_it.dart';

import '../../feature/workspaces/data/data_sources/workspace_remote_data_source.dart';
import '../../feature/workspaces/data/repositories/workspace_repository_impl.dart';
import '../../feature/workspaces/domain/repository/workspace_repository.dart';
import '../../feature/workspaces/domain/use_cases/get_workspaces_use_case.dart';
import '../../feature/workspaces/presentation/controller/workspace_cubit.dart';

class WorkspacesServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<WorkspaceRemoteDataSource>(
      () => WorkspaceRemoteDataSourceImpl(),
    );
    serviceLocator.registerLazySingleton<WorkspaceRepository>(
      () => WorkspaceRepositoryImpl(
        remoteDataSource: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<GetWorkspacesUseCase>(
      () => GetWorkspacesUseCase(serviceLocator()),
    );
    serviceLocator.registerFactory<WorkspaceCubit>(
      () => WorkspaceCubit(getWorkspacesUseCase: serviceLocator()),
    );
  }
}

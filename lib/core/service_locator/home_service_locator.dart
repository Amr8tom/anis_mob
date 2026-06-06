import 'package:get_it/get_it.dart';

import '../../features/home/data/data_sources/home_local_data_source.dart';
import '../../features/home/data/data_sources/home_remote_data_source.dart';
import '../../features/home/data/data_sources/workspace_attendance_remote_data_source.dart';
import '../../features/home/data/repositories/home_dummy_repository.dart';
import '../../features/home/data/repositories/home_repository_impl.dart';
import '../../features/home/data/repositories/workspace_attendance_dummy_repository.dart';
import '../../features/home/domain/repository/home_repository.dart';
import '../../features/home/domain/repository/workspace_attendance_repository.dart';
import '../../features/home/domain/use_cases/check_in_workspace_use_case.dart';
import '../../features/home/domain/use_cases/check_out_workspace_use_case.dart';
import '../../features/home/domain/use_cases/get_today_sessions_use_case.dart';
import '../../features/home/domain/use_cases/get_user_profile_use_case.dart';
import '../../features/home/presentation/controller/home_cubit.dart';

class HomeServiceLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    // Data sources
    serviceLocator.registerLazySingleton<HomeRemoteDataSource>(
      () => HomeRemoteDataSourceImpl(),
    );
    serviceLocator.registerLazySingleton<HomeLocalDataSource>(
      () => HomeLocalDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<WorkspaceAttendanceRemoteDataSource>(
      () => WorkspaceAttendanceDummyDataSourceImpl(),
    );

    // Repositories
    serviceLocator.registerLazySingleton<HomeRepositoryImpl>(
      () => HomeRepositoryImpl(
        remoteDataSource: serviceLocator(),
        localDataSource: serviceLocator(),
        networkInfo: serviceLocator(),
      ),
    );
    serviceLocator.registerLazySingleton<HomeRepository>(
      () => HomeDummyRepository(),
    );
    serviceLocator.registerLazySingleton<WorkspaceAttendanceRepository>(
      () => WorkspaceAttendanceDummyRepository(serviceLocator()),
    );

    // Use cases
    serviceLocator.registerLazySingleton<GetUserProfileUseCase>(
      () => GetUserProfileUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<GetTodaySessionsUseCase>(
      () => GetTodaySessionsUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<CheckInWorkspaceUseCase>(
      () => CheckInWorkspaceUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<CheckOutWorkspaceUseCase>(
      () => CheckOutWorkspaceUseCase(serviceLocator()),
    );

    // Cubit factory: new instance per route
    serviceLocator.registerFactory<HomeCubit>(
      () => HomeCubit(
        getUserProfileUseCase: serviceLocator(),
        getTodaySessionsUseCase: serviceLocator(),
        checkInWorkspaceUseCase: serviceLocator(),
        checkOutWorkspaceUseCase: serviceLocator(),
      ),
    );
  }
}

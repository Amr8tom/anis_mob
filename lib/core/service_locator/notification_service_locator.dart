// import 'package:get_it/get_it.dart';
// import '../../features/notifications/data/data_sources/notification_remote_data_sources.dart';
//
// import '../../features/notifications/data/data_sources/notification_local_data_sources.dart';
// import '../../features/notifications/data/repositories/notification_repositories.dart';
// import '../../features/notifications/domain/repositories/notification_repository.dart';
// import '../../features/notifications/domain/usecases/get_all_user_notification_use_case.dart';
// import '../../features/notifications/domain/usecases/mark_all_notification_as_read_use_case.dart';
// import '../../features/notifications/presentation/controller/notification_cubit.dart';
//
// class NotificationServiceLocator {
//   static Future execute({required GetIt serviceLocator}) async {
//     /// data sources
//     serviceLocator.registerLazySingleton<NotificationRemoteDataSources>(
//       () => NotificationRemoteDataSourcesImp(serviceLocator()),
//     );
//     serviceLocator.registerLazySingleton<NotificationLocalDataSources>(
//       () => NotificationLocalDataSourcesImp(),
//     );
//
//     /// Repositories
//     serviceLocator.registerLazySingleton<NotificationRepository>(
//       () => NotificationRepositoryImp(
//         serviceLocator(),
//         serviceLocator(),
//         serviceLocator(),
//       ),
//     );
//
//     /// Use Cases
//     serviceLocator.registerLazySingleton<GetAllUserNotificationUseCase>(
//       () => GetAllUserNotificationUseCase(serviceLocator()),
//     );
//
//     serviceLocator.registerLazySingleton<MarkAllNotificationAsReadUseCase>(
//       () => MarkAllNotificationAsReadUseCase(serviceLocator()),
//     );
//
//     /// Controllers
//     serviceLocator.registerFactory<NotificationCubit>(
//       () => NotificationCubit(
//         serviceLocator(),
//         serviceLocator(),
//       ),
//     );
//   }
// }

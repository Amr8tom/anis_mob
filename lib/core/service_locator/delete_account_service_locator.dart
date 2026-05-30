// import 'package:get_it/get_it.dart';
// import '../../features/delete_account/data/data_sources/loca_data_sources.dart';
// import '../../features/delete_account/data/data_sources/remote_data_sources.dart';
// import '../../features/delete_account/data/repository/repository.dart';
// import '../../features/delete_account/domain/repository/repository.dart';
// import '../../features/delete_account/domain/usecases/delete_account_use_case.dart';
// import '../../features/delete_account/presentation/controllers/delete_account_cubit.dart';
//
// class DeleteAccountServiceLocator {
//   static Future execute({required GetIt serviceLocator}) async {
//     /// data sources
//     serviceLocator.registerLazySingleton<DeleteAccountLocalDataSources>(
//       () => DeleteAccountLocalDataSourcesImp(),
//     );
//     serviceLocator.registerLazySingleton<DeleteRemoteDataSources>(
//       () => DeleteRemoteDataSourcesImp(serviceLocator()),
//     );
//
//     /// repositories
//     serviceLocator.registerLazySingleton<DeleteAccountRepository>(
//       () => DeleteAccountRepositoryImp(
//         serviceLocator(),
//         serviceLocator(),
//         serviceLocator(),
//       ),
//     );
//
//     /// use cases
//     serviceLocator.registerLazySingleton<DeleteAccountUseCase>(
//       () => DeleteAccountUseCase(serviceLocator()),
//     );
//
//     /// controller
//     serviceLocator.registerFactory<DeleteAccountCubit>(
//       () => DeleteAccountCubit(serviceLocator()),
//     );
//   }
// }

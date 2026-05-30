// import 'package:get_it/get_it.dart';
// import 'package:anis/features/auth/domain/usecases/change_password_use_case.dart';
//
// import '../../features/auth/data/data_sources/remote_data_sources.dart';
// import '../../features/auth/data/repositories/auth_repositories.dart';
// import '../../features/auth/domain/repositories/auth_repositories.dart';
// import '../../features/auth/domain/usecases/login_use_case.dart';
// import '../../features/auth/presentation/controller/login/login_cubit.dart';
//
// class AuthServiceLocator {
//   static Future<void> execute({required GetIt serviceLocator}) async {
//     /// data sources
//     // serviceLocator.registerLazySingleton<AuthLocalDataSources>(
//     //   () => AuthLocalDataSourcesImp(),
//     // );
//     serviceLocator.registerLazySingleton<AuthRemoteDataSources>(
//       () => AuthRemoteDataSourcesImp(serviceLocator()),
//     );
//
//     /// repositories
//     serviceLocator.registerLazySingleton<AuthRepositories>(
//       () => AuthRepositoriesImp(
//         serviceLocator(),
//         serviceLocator(),
//       ),
//     );
//
//     /// use cases
//     serviceLocator.registerLazySingleton<LoginUseCase>(
//       () => LoginUseCase(serviceLocator()),
//     );
//     serviceLocator.registerLazySingleton<ChangePasswordUseCase>(
//       () => ChangePasswordUseCase(serviceLocator()),
//     );
//
//     /// controller
//     serviceLocator.registerFactory<LoginCubit>(
//       () => LoginCubit(serviceLocator(), serviceLocator()),
//     );
//     // serviceLocator.registerFactory<RegisterCubit>(
//     //   () => RegisterCubit(serviceLocator()),
//     // );
//     // serviceLocator.registerFactory<SetPasswordCubit>(
//     //   () => SetPasswordCubit(serviceLocator()),
//     // );
//
//     // /// forget password controllers
//     // serviceLocator.registerFactory<ForgetPasswordCubit>(
//     //   () => ForgetPasswordCubit(serviceLocator()),
//     // );
//   }
// }

import 'package:data_connection_checker_tv/data_connection_checker.dart';
import 'package:get_it/get_it.dart';

import '../connection/checkNetwork.dart';
import '../dio/dio_helper.dart';
import 'auth_service_locator.dart';
import 'buddy_service_locator.dart';
import 'home_service_locator.dart';
import 'language_service_locator.dart';
import 'navigation_servise_locator.dart';
import 'profile_service_locator.dart';
import 'workspaces_service_locator.dart';

final serviceLocator = GetIt.instance;

class DI {
  static execute() async {
    // ── Core ──────────────────────────────────────────────────────────────────
    serviceLocator.registerLazySingleton(() => DioHelper());
    serviceLocator.registerLazySingleton(() => DataConnectionChecker());
    serviceLocator.registerLazySingleton<NetworkInfo>(
      () => NetworkInfoImpl(serviceLocator()),
    );

    // ── Features ──────────────────────────────────────────────────────────────
    await AuthServiceLocator.execute(serviceLocator: serviceLocator);
    await NavigationServiseLocator.execute(serviceLocator: serviceLocator);
    await HomeServiceLocator.execute(serviceLocator: serviceLocator);

    await BuddyServiceLocator.execute(serviceLocator: serviceLocator);
    await WorkspacesServiceLocator.execute(serviceLocator: serviceLocator);
    await ProfileServiceLocator.execute(serviceLocator: serviceLocator);

    // ── Utilities ─────────────────────────────────────────────────────────────
    await LanguageServiceLocator.execute(serviceLocator: serviceLocator);
  }
}

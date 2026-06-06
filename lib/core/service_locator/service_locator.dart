import 'package:data_connection_checker_tv/data_connection_checker.dart';
import 'package:get_it/get_it.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../connection/check_network.dart';
import '../dio/dio_helper.dart';
import '../local_storage/local_storage.dart';
import '../local_storage/session_storage/session_storage.dart';
import '../local_storage/session_storage/session_storage_impl.dart';
import '../local_storage/shared_preferences_local_storage.dart';
import 'auth_service_locator.dart';
import 'buddy_service_locator.dart';
import 'home_service_locator.dart';
import 'language_service_locator.dart';
import 'navigation_servise_locator.dart';
import 'onboarding_service_locator.dart';
import 'profile_service_locator.dart';
import 'workspaces_service_locator.dart';

final serviceLocator = GetIt.instance;

class DI {
  static execute() async {
    // ── Core ──────────────────────────────────────────────────────────────────
    final sharedPreferences = await SharedPreferences.getInstance();
    serviceLocator.registerLazySingleton<SharedPreferences>(
      () => sharedPreferences,
    );
    serviceLocator.registerLazySingleton<LocalStorage>(
      () => SharedPreferencesLocalStorage(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<SessionStorage>(
      () => SessionStorageImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(() => DioHelper(serviceLocator()));
    serviceLocator.registerLazySingleton(() => DataConnectionChecker());
    serviceLocator.registerLazySingleton<NetworkInfo>(
      () => NetworkInfoImpl(serviceLocator()),
    );

    // ── Features ──────────────────────────────────────────────────────────────
    await OnboardingServiceLocator.execute(serviceLocator: serviceLocator);
    await AuthServiceLocator.execute(serviceLocator: serviceLocator);
    await NavigationServiseLocator.execute(serviceLocator: serviceLocator);
    await HomeServiceLocator.execute(serviceLocator: serviceLocator);

    await WorkspacesServiceLocator.execute(serviceLocator: serviceLocator);
    await BuddyServiceLocator.execute(serviceLocator: serviceLocator);
    await ProfileServiceLocator.execute(serviceLocator: serviceLocator);

    // ── Utilities ─────────────────────────────────────────────────────────────
    await LanguageServiceLocator.execute(serviceLocator: serviceLocator);
  }
}

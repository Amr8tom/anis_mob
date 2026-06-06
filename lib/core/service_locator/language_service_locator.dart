import 'package:get_it/get_it.dart';

import '../../features/language/data/data_sources/language_local_data_source.dart';
import '../../features/language/data/repositories/language_repository_impl.dart';
import '../../features/language/domain/repositories/language_repository.dart';
import '../../features/language/domain/use_cases/cache_language_use_case.dart';
import '../../features/language/domain/use_cases/get_language_use_case.dart';
import '../../features/language/presentation/controller/language_cubit.dart';

/// Service locator for the Language feature
/// This class registers the LanguageCubit as a singleton in the service locator.

class LanguageServiceLocator {
  static Future execute({required GetIt serviceLocator}) async {
    serviceLocator.registerLazySingleton<LanguageLocalDataSource>(
      () => LanguageLocalDataSourceImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton<LanguageRepository>(
      () => LanguageRepositoryImpl(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => GetLanguageUseCase(serviceLocator()),
    );
    serviceLocator.registerLazySingleton(
      () => CacheLanguageUseCase(serviceLocator()),
    );
    serviceLocator.registerSingleton<LanguageCubit>(
      LanguageCubit(serviceLocator(), serviceLocator()),
    );
  }
}

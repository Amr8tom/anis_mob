import 'package:get_it/get_it.dart';
import '../../feature/language/presentation/controller/language_cubit.dart';
/// Service locator for the Language feature
/// This class registers the LanguageCubit as a singleton in the service locator.

class LanguageServiceLocator {
  static Future execute({required GetIt serviceLocator}) async {
    serviceLocator.registerSingleton<LanguageCubit>(LanguageCubit());

  }
}

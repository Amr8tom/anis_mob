import 'package:get_it/get_it.dart';

import '../../feature/navigation/presentation/controllers/navigation_cubit.dart';

class NavigationServiseLocator {
  static Future<void> execute({required GetIt serviceLocator}) async {
    serviceLocator.registerFactory<NavigationCubit>(
      () => NavigationCubit(),
    );
  }
}

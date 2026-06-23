import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'core/notifications/push_messaging_service.dart';
import 'core/service_locator/service_locator.dart';
import 'core/utils/helpers/bloc_oberver.dart';
import 'features/app/app.dart';
import 'features/language/presentation/controller/language_cubit.dart';
import 'firebase_options.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );
  // Must be registered before runApp so background/terminated pushes are handled.
  FirebaseMessaging.onBackgroundMessage(firebaseMessagingBackgroundHandler);

  await ScreenUtil.ensureScreenSize();
  await DI.execute();

  // Wire foreground display + the local-notifications channel once at startup.
  await serviceLocator<PushMessagingService>().init();

  await serviceLocator<LanguageCubit>().init();
  Bloc.observer = MyBlocObserver();
  runApp(const AnisApp());
}

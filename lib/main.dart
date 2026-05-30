// import 'package:firebase_core/firebase_core.dart';       // TODO: enable when Firebase is configured
// import 'package:firebase_crashlytics/firebase_crashlytics.dart';
// import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'core/device/device_utility.dart';
import 'core/service_locator/service_locator.dart';
import 'core/utils/helpers/bloc_oberver.dart';
// import 'core/utils/helpers/permissions_services.dart'; // TODO: enable when Firebase is configured
import 'feature/app/app.dart';
import 'feature/language/presentation/controller/language_cubit.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  // await Firebase.initializeApp();
  await ScreenUtil.ensureScreenSize();
  await DDeviceUtils.initCacheHelper();
  await DI.execute();
  await serviceLocator<LanguageCubit>().init();
  Bloc.observer = MyBlocObserver();
  // TODO: enable when Firebase is configured
  // FirebaseMessaging messaging = FirebaseMessaging.instance;
  // await messaging.requestPermission(alert: true, badge: true, sound: true);
  // await PermissionsService.notifications();
  // FlutterError.onError = FirebaseCrashlytics.instance.recordFlutterError;
  // await FirebaseCrashlytics.instance.setCrashlyticsCollectionEnabled(true);
  runApp(const AnisApp());
}

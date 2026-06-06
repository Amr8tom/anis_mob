import 'package:flutter/cupertino.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'core/service_locator/service_locator.dart';
import 'core/utils/helpers/bloc_oberver.dart';
import 'features/app/app.dart';
import 'features/language/presentation/controller/language_cubit.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ScreenUtil.ensureScreenSize();
  await DI.execute();
  await serviceLocator<LanguageCubit>().init();
  Bloc.observer = MyBlocObserver();
  runApp(const AnisApp());
}

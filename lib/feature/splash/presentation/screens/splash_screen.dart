import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/local_storage/cache_helper.dart';
import '../../../../core/local_storage/cache_keys.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../core/service_locator/service_locator.dart';
import '../../../language/presentation/controller/language_cubit.dart';
import '../widgets/splash_background.dart';
import '../widgets/splash_language_section.dart';
import '../widgets/splash_logo_section.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  final token = CacheHelper.getString(key: CacheKeys.token);

  bool get _hasToken => token != null && token!.trim().isNotEmpty;

  @override
  void initState() {
    super.initState();
    _setSystemUIOverlayStyle();
    if (_hasToken) _delayBeforeNavigation();
  }

  void _setSystemUIOverlayStyle() {
    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.light,
        systemNavigationBarColor: ColorRes.anisAuthBgBottom,
        systemNavigationBarIconBrightness: Brightness.light,
      ),
    );
  }

  @override
  void dispose() {
    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
        statusBarColor: ColorRes.primary,
        statusBarIconBrightness: Brightness.light,
        systemNavigationBarColor: ColorRes.primary,
        systemNavigationBarIconBrightness: Brightness.light,
      ),
    );
    super.dispose();
  }

  Future<void> _delayBeforeNavigation() async {
    await Future.delayed(const Duration(milliseconds: 2500));
    if (!mounted) return;
    Navigator.of(context).pushReplacementNamed(DRoutesName.navigationMenuRoute);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SplashBackground(
        child: SafeArea(
          child: Center(
            child: Column(
              children: [
                const Spacer(flex: 2),
                const SplashLogoSection(),
                const Spacer(flex: 3),
                if (!_hasToken)
                  SplashLanguageSection(
                    onLanguageSelected: (code) {
                      serviceLocator<LanguageCubit>().changeLanguage(code);
                      CacheHelper.cacheLanguage(code);
                      Navigator.of(context)
                          .pushReplacementNamed(DRoutesName.userInfoRoute);
                    },
                    onLoginTap: () => Navigator.of(context)
                        .pushReplacementNamed(DRoutesName.loginRoute),
                  )
                else
                  const Sizer(height: 42),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

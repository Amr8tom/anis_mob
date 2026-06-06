import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../core/service_locator/service_locator.dart';
import '../../../auth/domain/use_cases/get_auth_token_use_case.dart';
import '../../../onboarding/domain/use_cases/get_onboarding_status_use_case.dart';
import '../widgets/splash_background.dart';
import '../widgets/splash_logo_section.dart';
import '../../../../generated/l10n.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen>
    with SingleTickerProviderStateMixin {
  late final AnimationController _bottomFade;
  late final Animation<double> _fadeAnim;
  late final Animation<Offset> _slideAnim;

  @override
  void initState() {
    super.initState();
    _bottomFade = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 900),
    );
    _fadeAnim = CurvedAnimation(parent: _bottomFade, curve: Curves.easeOut);
    _slideAnim = Tween<Offset>(
      begin: const Offset(0, 0.4),
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _bottomFade, curve: Curves.easeOutCubic));

    _setSystemUIOverlayStyle();
    // Small delay so the bottom animates in after the logo
    Future.delayed(const Duration(milliseconds: 900), () {
      if (mounted) _bottomFade.forward();
    });
    _resolveStartupRoute();
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
    _bottomFade.dispose();
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

  Future<void> _navigateAfterSplash(String routeName) async {
    await Future.delayed(const Duration(milliseconds: 2600));
    if (!mounted) return;
    Navigator.of(context).pushReplacementNamed(routeName);
  }

  Future<void> _resolveStartupRoute() async {
    final result = await serviceLocator<GetAuthTokenUseCase>().call();
    final token = result.getOrElse(() => null);
    final hasToken = token != null && token.trim().isNotEmpty;

    if (hasToken) {
      await _navigateAfterSplash(DRoutesName.navigationMenuRoute);
      return;
    }

    final onboardingResult =
        await serviceLocator<GetOnboardingStatusUseCase>().call();
    final hasCompletedOnboarding = onboardingResult.getOrElse(() => false);
    await _navigateAfterSplash(
      hasCompletedOnboarding
          ? DRoutesName.loginRoute
          : DRoutesName.onBoardingRoute,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SplashBackground(
        child: SafeArea(
          child: Column(
            children: [
              const Spacer(flex: 2),
              const SplashLogoSection(),
              const Spacer(flex: 3),
              // ── Bottom — tagline + loading bar ──────────────────
              FadeTransition(
                opacity: _fadeAnim,
                child: SlideTransition(
                  position: _slideAnim,
                  child: Padding(
                    padding: EdgeInsets.symmetric(
                      horizontal: AppSizes.xl,
                    ),
                    child: Column(
                      children: [
                        Text(
                          S.current.splashScreenText,
                          textAlign: TextAlign.center,
                          style: Theme.of(context)
                              .textTheme
                              .bodySmall
                              ?.copyWith(
                                color: ColorRes.white.withValues(alpha: 0.52),
                                height: 1.6,
                                letterSpacing: 0.2,
                              ),
                        ),
                        const Sizer(height: 18),
                        // Thin indeterminate progress pill
                        ClipRRect(
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusXXLg),
                          child: SizedBox(
                            width: 100,
                            height: 3,
                            child: LinearProgressIndicator(
                              backgroundColor:
                                  ColorRes.white.withValues(alpha: 0.14),
                              valueColor: const AlwaysStoppedAnimation<Color>(
                                ColorRes.anisGreen,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
              const Sizer(height: 48),
            ],
          ),
        ),
      ),
    );
  }
}

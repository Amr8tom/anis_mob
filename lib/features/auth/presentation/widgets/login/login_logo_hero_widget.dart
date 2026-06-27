import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/animations/animated_entrance.dart';
import 'package:anis/common/widgets/animations/continuous_float.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';
import 'package:lottie/lottie.dart';

/// Premium login header: branded green hero with logo, title, and tagline.
class LoginLogoHero extends StatelessWidget {
  const LoginLogoHero({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      width: double.infinity,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.appBarHeight * 1.65,
        AppSizes.padding,
        30.h,
      ),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            ColorRes.anisGreen,
            ColorRes.anisGreen.withValues(alpha: 0.92),
            ColorRes.anisNavy.withValues(alpha: 0.95),
          ],
        ),
      ),
      child: Stack(
        children: [
          PositionedDirectional(
            top: -28.h,
            end: -34.w,
            child: ContinuousFloat(
              period: const Duration(seconds: 6),
              translateY: 12,
              scaleAmplitude: 0.04,
              child: Container(
                width: 128.w,
                height: 128.w,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: ColorRes.white.withValues(alpha: 0.08),
                ),
              ),
            ),
          ),
          PositionedDirectional(
            bottom: -50.h,
            start: 90.w,
            child: ContinuousFloat(
              period: const Duration(seconds: 7),
              delay: const Duration(milliseconds: 700),
              translateY: 16,
              scaleAmplitude: 0.05,
              child: Container(
                width: 160.w,
                height: 160.w,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: ColorRes.white.withValues(alpha: 0.045),
                ),
              ),
            ),
          ),
          Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              AnimatedEntrance(
                delay: const Duration(milliseconds: 120),
                offsetY: 18,
                child: ContinuousFloat(
                  period: const Duration(milliseconds: 3600),
                  translateY: 4,
                  scaleAmplitude: 0.03,
                  child: Container(
                    padding: EdgeInsets.all(7.w),
                    decoration: BoxDecoration(
                      color: ColorRes.white.withValues(alpha: 0.16),
                      shape: BoxShape.circle,
                      border: Border.all(
                        color: ColorRes.white.withValues(alpha: 0.24),
                        width: 1,
                      ),
                    ),
                    child: Container(
                      width: 120.w,
                      height: 120.h,
                      decoration: BoxDecoration(
                        color: ColorRes.white,
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: ColorRes.anisNavy.withValues(alpha: 0.22),
                            blurRadius: 18,
                            offset: const Offset(0, 8),
                          ),
                        ],
                      ),
                      padding: EdgeInsets.all(10.w),
                      child: Lottie.asset(AssetRes.aniLottie),
                    ),
                  ),
                ),
              ),
              const Sizer(height: 6),
              AnimatedEntrance(
                delay: const Duration(milliseconds: 240),
                child: Text(
                  S.current.login,
                  style: tt.headlineSmall?.copyWith(
                    fontWeight: FontWeight.w900,
                    color: ColorRes.white,
                    height: 1.15,
                  ),
                ),
              ),
              const Sizer(height: 8),
              AnimatedEntrance(
                delay: const Duration(milliseconds: 330),
                child: ConstrainedBox(
                  constraints: BoxConstraints(maxWidth: 300.w),
                  child: Text(
                    S.current.appTagline,
                    style: tt.bodyMedium?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.78),
                      fontSize: AppSizes.fontSizeSm,
                      height: 1.45,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import 'splash_language_card.dart';

class SplashLanguageSection extends StatelessWidget {
  const SplashLanguageSection({
    super.key,
    required this.onLanguageSelected,
    required this.onLoginTap,
  });

  final void Function(String) onLanguageSelected;
  final VoidCallback onLoginTap;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsetsDirectional.symmetric(
        horizontal: AppSizes.padding * 1.5,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            S.current.splashChooseLanguage,
            style: TextStyle(
              fontFamily: 'Cairo',
              fontSize: AppSizes.fontSizeMd,
              fontWeight: FontWeight.w700,
              color: Colors.white.withValues(alpha: 0.90),
            ),
          ),

          Sizer(height: AppSizes.md),

          Row(
            children: [
              Expanded(
                child: SplashLanguageCard(
                  flag: '🇬🇧',
                  languageName: S.current.languageEnglish,
                  nativeHint: S.current.splashEnglishHint,
                  gradColors: [
                    Colors.white.withValues(alpha: 0.08),
                    ColorRes.anisGreen.withValues(alpha: 0.30),
                  ],
                  glowColor: ColorRes.anisGreen,
                  onTap: () => onLanguageSelected('en'),
                ),
              ),
              Sizer(width: AppSizes.md),
              Expanded(
                child: SplashLanguageCard(
                  flag: '🇸🇦',
                  languageName: S.current.languageArabic,
                  nativeHint: S.current.splashArabicHint,
                  gradColors: [
                    ColorRes.anisGreen.withValues(alpha: 0.55),
                    ColorRes.anisButtonGreen.withValues(alpha: 0.30),
                  ],
                  glowColor: ColorRes.anisButtonGreen,
                  onTap: () => onLanguageSelected('ar'),
                ),
              ),
            ],
          ),

          Sizer(height: AppSizes.ld),

          Row(
            children: [
              Expanded(
                child: Divider(
                  color: Colors.white.withValues(alpha: 0.15),
                  thickness: 1,
                ),
              ),
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 10.w),
                child: Text(
                  S.current.or,
                  style: TextStyle(
                    fontFamily: 'Cairo',
                    fontSize: 12.sp,
                    color: Colors.white.withValues(alpha: 0.40),
                  ),
                ),
              ),
              Expanded(
                child: Divider(
                  color: Colors.white.withValues(alpha: 0.15),
                  thickness: 1,
                ),
              ),
            ],
          ),

          Sizer(height: AppSizes.md),

          // Login button
          GestureDetector(
            onTap: () {
              HapticFeedback.lightImpact();
              onLoginTap();
            },
            child: Container(
              width: double.infinity,
              height: AppSizes.buttonHeight,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
                border: Border.all(
                  color: ColorRes.anisGreen.withValues(alpha: 0.50),
                  width: 1.5,
                ),
                color: ColorRes.anisGreen.withValues(alpha: 0.10),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.login_rounded,
                    color: ColorRes.anisGreen,
                    size: 18.sp,
                  ),
                  SizedBox(width: 8.w),
                  Text(
                    S.current.login,
                    style: TextStyle(
                      fontFamily: 'Cairo',
                      fontSize: AppSizes.fontSizeSm,
                      fontWeight: FontWeight.w700,
                      color: ColorRes.anisGreen,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

/// Upwork-style top banner: solid green, left-aligned logo + headline.
class LoginLogoHero extends StatelessWidget {
  const LoginLogoHero({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Container(
      width: double.infinity,
      color: ColorRes.anisGreen,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.appBarHeight * 1.8,
        AppSizes.padding,
        28.h,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          /// ── Logo circle ──────────────────────────────────────
          Container(
            width: 52.w,
            height: 52.w,
            decoration: BoxDecoration(
              color: ColorRes.white,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisNavy.withValues(alpha: 0.18),
                  blurRadius: 12,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            padding: EdgeInsets.all(AppSizes.sm),
            child: Image.asset(AssetRes.logo, fit: BoxFit.contain),
          ),

          const Sizer(height: 20),

          /// ── "Log in to Anis" headline ─────────────────────────
          Text(
            S.current.login,
            style: tt.headlineSmall?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.white,
              height: 1.2,
            ),
          ),

          const Sizer(height: 6),

          Text(
            S.current.appTagline,
            style: tt.bodyMedium?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.75),
              fontSize: AppSizes.fontSizeSm,
            ),
          ),
        ],
      ),
    );
  }
}

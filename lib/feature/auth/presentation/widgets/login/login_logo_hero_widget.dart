import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

class LoginLogoHero extends StatelessWidget {
  const LoginLogoHero({super.key});

  @override
  Widget build(BuildContext context) {
    return Stack(
      alignment: Alignment.center,
      children: [
        // Outer ambient glow
        Container(
          width: 200.w,
          height: 200.w,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: ColorRes.anisGreen.withValues(alpha: 0.07),
          ),
        ),
        // Mid glow
        Container(
          width: 168.w,
          height: 168.w,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: ColorRes.anisGreen.withValues(alpha: 0.10),
          ),
        ),
        Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Logo circle
            Container(
              width: 138.w,
              height: 138.w,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: const LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [ColorRes.anisGreen, ColorRes.anisButtonGreen],
                ),
                boxShadow: [
                  BoxShadow(
                    color: ColorRes.anisGreen.withValues(alpha: 0.60),
                    blurRadius: 40,
                    spreadRadius: 6,
                  ),
                  BoxShadow(
                    color: ColorRes.anisButtonGreen.withValues(alpha: 0.25),
                    blurRadius: 20,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Padding(
                padding: EdgeInsets.all(10.w),
                child: Image.asset(
                  AssetRes.appIcon,
                  fit: BoxFit.contain,
                ),
              ),
            ),
            const Sizer(height: 18),

            // App name
            Text(
              'Anis',
              style: Theme.of(context).textTheme.displaySmall?.copyWith(
                fontWeight: FontWeight.w900,
                color: ColorRes.white,
                letterSpacing: 1.5,
              ),
            ),

            const Sizer(height: 6),

            Text(
              S.current.appTagline,
              style: Theme.of(context).textTheme.titleMedium?.copyWith(
                fontSize: AppSizes.fontSizeSm,
                color: ColorRes.white.withValues(alpha: 0.55),
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ],
    );
  }
}

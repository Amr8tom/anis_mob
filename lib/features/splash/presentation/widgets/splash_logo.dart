import 'package:flutter/material.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';

class SplashLogo extends StatelessWidget {
  final Animation<double> fadeAnimation;

  const SplashLogo({super.key, required this.fadeAnimation});

  @override
  Widget build(BuildContext context) {
    return FadeTransition(
      opacity: fadeAnimation,
      child: Text(
        S.current.appName,
        style: TextStyle(
          fontSize: AppSizes.fontSizeXLg * 2,
          fontWeight: FontWeight.bold,
          color: ColorRes.white,
          letterSpacing: 2.0,
          shadows: [
            Shadow(
              offset: Offset(2, 2),
              blurRadius: AppSizes.blurSmall,
              color: ColorRes.black.withValues(alpha: 0.3),
            ),
          ],
        ),
      ),
    );
  }
}

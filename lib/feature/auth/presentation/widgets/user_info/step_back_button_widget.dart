import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

class StepBackButton extends StatelessWidget {
  final VoidCallback? onTap;

  const StepBackButton({super.key, this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: AppSizes.iconXLarge * 1.2,
        height: AppSizes.iconXLarge * 1.2,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: ColorRes.anisAuthContainer,
          border: Border.all(
            color: ColorRes.anisGreen.withValues(alpha: 0.45),
            width: 1.5,
          ),
        ),
        child: Icon(
          Icons.arrow_back_ios_new_rounded,
          color: ColorRes.white.withValues(alpha: 0.80),
          size: AppSizes.iconSm,
        ),
      ),
    );
  }
}

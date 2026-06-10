import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class StepperButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback onTap;
  const StepperButton({super.key, required this.icon, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: AppSizes.iconXLarge + AppSizes.sm,
        height: AppSizes.iconXLarge + AppSizes.sm,
        decoration: BoxDecoration(
          color: ColorRes.anisGreen.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
              color: ColorRes.anisGreen.withValues(alpha: 0.3), width: 1),
        ),
        child: Icon(icon, size: AppSizes.iconSm, color: ColorRes.anisGreen),
      ),
    );
  }
}

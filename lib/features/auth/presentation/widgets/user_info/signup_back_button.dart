import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

class SignUpBackButton extends StatelessWidget {
  final VoidCallback? onTap;
  const SignUpBackButton({super.key, this.onTap});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: AppSizes.buttonHeight,
      height: AppSizes.buttonHeight,
      child: TextButton(
        onPressed: onTap,
        style: TextButton.styleFrom(
          foregroundColor: ColorRes.anisTextMuted,
          overlayColor: ColorRes.anisChipBg,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            side: const BorderSide(color: ColorRes.anisInputBorder),
          ),
          padding: EdgeInsets.zero,
        ),
        child: const Icon(
          Icons.arrow_back_ios_new_rounded,
          size: 18,
          color: ColorRes.anisTextMuted,
        ),
      ),
    );
  }
}

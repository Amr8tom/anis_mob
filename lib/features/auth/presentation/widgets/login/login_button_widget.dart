import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

/// Upwork-style solid green login button.
class LoginButton extends StatelessWidget {
  final VoidCallback? onTap;
  final bool isLoading;

  const LoginButton({
    super.key,
    required this.onTap,
    this.isLoading = false,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: AppSizes.buttonHeight,
      child: ElevatedButton(
        onPressed: onTap,
        style: ElevatedButton.styleFrom(
          backgroundColor: ColorRes.anisGreen,
          foregroundColor: ColorRes.white,
          disabledBackgroundColor: ColorRes.anisGreen.withValues(alpha: 0.60),
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          ),
        ),
        child: isLoading
            ? const SizedBox(
                width: 22,
                height: 22,
                child: CircularProgressIndicator(
                  strokeWidth: 2.5,
                  valueColor: AlwaysStoppedAnimation<Color>(ColorRes.white),
                ),
              )
            : Text(
                S.current.login,
                style: Theme.of(context).textTheme.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: ColorRes.white,
                    ),
              ),
      ),
    );
  }
}

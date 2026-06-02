import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

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
    return GestureDetector(
      onTap: onTap,
      child: Container(
        height: AppSizes.buttonHeight,
        width: double.infinity,
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [ColorRes.anisGreen, ColorRes.anisButtonGreen],
            begin: Alignment.centerRight,
            end: Alignment.centerLeft,
          ),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisGreen.withValues(alpha: 0.40),
              blurRadius: 16,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: Center(
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
                    fontWeight: FontWeight.bold,
                    color: ColorRes.white,
                  ),
                ),
        ),
      ),
    );
  }
}

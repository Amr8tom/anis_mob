import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Light-mode input field — Upwork style.
/// Label above, clean border, green focus ring.
class LoginDarkField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final IconData prefixIcon;
  final bool obscure;
  final Widget? suffixIcon;
  final String? Function(String?)? validator;
  final TextInputType keyboardType;

  const LoginDarkField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    required this.prefixIcon,
    this.obscure = false,
    this.suffixIcon,
    this.validator,
    this.keyboardType = TextInputType.text,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
                fontWeight: FontWeight.w600,
                color: ColorRes.anisTextDark,
              ),
        ),
        const Sizer(height: 8),
        TextFormField(
          controller: controller,
          obscureText: obscure,
          keyboardType: keyboardType,
          validator: validator,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: ColorRes.anisTextDark,
                fontWeight: FontWeight.w500,
                letterSpacing: obscure ? 3 : 0,
              ),
          cursorColor: ColorRes.anisGreen,
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: ColorRes.anisTextSecondary.withValues(alpha: 0.65),
                  letterSpacing: 0,
                ),
            prefixIcon: Icon(
              prefixIcon,
              color: ColorRes.anisTextSecondary,
              size: 20,
            ),
            suffixIcon: suffixIcon,
            filled: true,
            fillColor: ColorRes.anisInputBg,
            contentPadding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              vertical: AppSizes.md,
            ),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide:
                  const BorderSide(color: ColorRes.anisInputBorder, width: 1.5),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide: BorderSide(
                color: ColorRes.anisGreen,
                width: 2,
              ),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide:
                  const BorderSide(color: ColorRes.anisErrorRed, width: 1.5),
            ),
            focusedErrorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide:
                  const BorderSide(color: ColorRes.anisErrorRed, width: 2),
            ),
            errorStyle: Theme.of(context).textTheme.labelSmall?.copyWith(
                  color: ColorRes.anisErrorRed,
                ),
          ),
        ),
      ],
    );
  }
}

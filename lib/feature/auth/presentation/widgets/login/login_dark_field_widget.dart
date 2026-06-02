import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

class LoginDarkField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final IconData prefixIcon;
  final bool obscure;
  final Widget? suffixIcon;
  final String? Function(String?)? validator;

  const LoginDarkField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    required this.prefixIcon,
    this.obscure = false,
    this.suffixIcon,
    this.validator,
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
            color: ColorRes.white.withValues(alpha: 0.70),
          ),
        ),
        const Sizer(height: 6),
        TextFormField(
          controller: controller,
          obscureText: obscure,
          validator: validator,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            color: ColorRes.white,
            fontWeight: FontWeight.w500,
            letterSpacing: obscure ? 4 : 0,
          ),
          cursorColor: ColorRes.anisGreen,
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.28),
              letterSpacing: 0,
            ),
            prefixIcon: Icon(
              prefixIcon,
              color: ColorRes.anisGreen.withValues(alpha: 0.80),
            ),
            suffixIcon: suffixIcon,
            filled: true,
            fillColor: ColorRes.anisAuthContainer,
            contentPadding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              vertical: AppSizes.md,
            ),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              borderSide: BorderSide.none,
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              borderSide: BorderSide(
                color: ColorRes.anisGreen.withValues(alpha: 0.25),
                width: 1.5,
              ),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              borderSide: BorderSide(
                color: ColorRes.anisGreen.withValues(alpha: 0.70),
                width: 1.5,
              ),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              borderSide: const BorderSide(color: ColorRes.anisErrorRed, width: 1.5),
            ),
            focusedErrorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              borderSide: const BorderSide(color: ColorRes.anisErrorRed, width: 1.5),
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

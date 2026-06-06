import 'package:flutter/material.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

/// Consistently styled [TextFormField] used across all create-session sections.
class SessionStyledField extends StatelessWidget {
  final TextEditingController controller;
  final String hint;
  final int maxLines;
  final String? Function(String?)? validator;
  final void Function(String)? onSubmitted;
  final void Function(String)? onChanged;
  final IconData? prefixIcon;

  const SessionStyledField({
    super.key,
    required this.controller,
    required this.hint,
    this.maxLines = 1,
    this.validator,
    this.onSubmitted,
    this.onChanged,
    this.prefixIcon,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      validator: validator,
      onFieldSubmitted: onSubmitted,
      onChanged: onChanged,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            color: ColorRes.anisNavy,
          ),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: ColorRes.anisHintText,
            ),
        prefixIcon: prefixIcon != null
            ? Icon(prefixIcon, size: AppSizes.iconSm, color: ColorRes.anisGreen)
            : null,
        filled: true,
        fillColor: ColorRes.anisChipBg,
        contentPadding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.sm + 4,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisLine, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisGreen, width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisErrorRed, width: 1),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisErrorRed, width: 1.5),
        ),
      ),
    );
  }
}

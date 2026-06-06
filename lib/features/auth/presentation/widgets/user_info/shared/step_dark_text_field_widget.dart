import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Dark-themed text input used on the user-info wizard steps.
class StepDarkTextField extends StatelessWidget {
  final TextEditingController controller;
  final String hint;
  final IconData prefixIcon;
  final ValueChanged<String>? onChanged;
  final TextInputType keyboardType;

  const StepDarkTextField({
    super.key,
    required this.controller,
    required this.hint,
    required this.prefixIcon,
    this.onChanged,
    this.keyboardType = TextInputType.text,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorRes.anisAuthContainer,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(color: ColorRes.anisAuthBorder, width: 1.5),
      ),
      child: TextField(
        controller: controller,
        keyboardType: keyboardType,
        style: Theme.of(context).textTheme.bodyLarge?.copyWith(
              color: ColorRes.white,
              fontWeight: FontWeight.w600,
            ),
        cursorColor: ColorRes.anisGreen,
        onChanged: onChanged,
        decoration: InputDecoration(
          hintText: hint,
          hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: ColorRes.white.withValues(alpha: 0.28),
              ),
          prefixIcon: Icon(
            prefixIcon,
            color: ColorRes.anisGreen.withValues(alpha: 0.80),
          ),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            borderSide: BorderSide.none,
          ),
          fillColor: ColorRes.transparent,
          filled: true,
          contentPadding: EdgeInsets.symmetric(
            horizontal: 16.w,
            vertical: 18.h,
          ),
        ),
      ),
    );
  }
}

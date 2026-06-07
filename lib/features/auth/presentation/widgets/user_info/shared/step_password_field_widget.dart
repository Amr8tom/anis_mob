import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Dark password input with visibility toggle + validity border colour.
class StepPasswordField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final bool obscure;
  final bool isValid;
  final VoidCallback onToggle;

  const StepPasswordField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    required this.obscure,
    required this.onToggle,
    this.isValid = false,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: Theme.of(context).textTheme.labelMedium?.copyWith(
                fontWeight: FontWeight.w600,
                color: ColorRes.white.withValues(alpha: 0.70),
              ),
        ),
        const Sizer(height: 6),
        AnimatedContainer(
          duration: const Duration(milliseconds: 250),
          decoration: BoxDecoration(
            color: ColorRes.anisAuthContainer,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            border: Border.all(
              color: controller.text.isEmpty
                  ? ColorRes.anisGreen.withValues(alpha: 0.30)
                  : isValid
                      ? ColorRes.anisButtonGreen.withValues(alpha: 0.60)
                      : ColorRes.anisErrorRed.withValues(alpha: 0.55),
              width: 1.5,
            ),
          ),
          child: TextField(
            controller: controller,
            obscureText: obscure,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  color: ColorRes.white,
                  fontWeight: FontWeight.w600,
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
                Icons.lock_outline_rounded,
                color: ColorRes.anisGreen.withValues(alpha: 0.80),
              ),
              suffixIcon: GestureDetector(
                onTap: onToggle,
                child: Icon(
                  obscure
                      ? Icons.visibility_off_outlined
                      : Icons.visibility_outlined,
                  color: ColorRes.white.withValues(alpha: 0.45),
                  size: 20.sp,
                ),
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
        ),
      ],
    );
  }
}

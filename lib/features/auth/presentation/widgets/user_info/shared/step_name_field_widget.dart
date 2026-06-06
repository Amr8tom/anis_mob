import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Full-name input with animated border + character counter + inline error.
class StepNameField extends StatelessWidget {
  final TextEditingController controller;
  final String hint;
  final String? errorText;
  final ValueChanged<String> onChanged;

  const StepNameField({
    super.key,
    required this.controller,
    required this.hint,
    required this.onChanged,
    this.errorText,
  });

  bool get _hasError => errorText != null;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          decoration: BoxDecoration(
            color: ColorRes.anisAuthContainer,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            border: Border.all(
              color: _hasError
                  ? ColorRes.anisErrorRed
                  : ColorRes.anisGreen.withValues(alpha: 0.30),
              width: 1.5,
            ),
            boxShadow: _hasError
                ? [
                    BoxShadow(
                      color: ColorRes.anisErrorRed.withValues(alpha: 0.18),
                      blurRadius: 8,
                      offset: const Offset(0, 2),
                    ),
                  ]
                : null,
          ),
          child: TextField(
            controller: controller,
            inputFormatters: [LengthLimitingTextInputFormatter(100)],
            maxLength: 100,
            buildCounter: (_,
                    {required currentLength, required isFocused, maxLength}) =>
                Text(
              '$currentLength / $maxLength',
              style: Theme.of(context).textTheme.labelSmall?.copyWith(
                    color: currentLength < 2 && currentLength > 0
                        ? ColorRes.anisErrorRed
                        : ColorRes.white.withValues(alpha: 0.35),
                  ),
            ),
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
                Icons.person_rounded,
                color: _hasError
                    ? ColorRes.anisErrorRed.withValues(alpha: 0.80)
                    : ColorRes.anisGreen.withValues(alpha: 0.80),
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
        AnimatedSize(
          duration: const Duration(milliseconds: 200),
          curve: Curves.easeOut,
          child: _hasError
              ? Padding(
                  padding: EdgeInsets.only(top: 6.h, left: 12.w),
                  child: Row(
                    children: [
                      Icon(
                        Icons.error_outline_rounded,
                        color: ColorRes.anisErrorRed,
                        size: 13.sp,
                      ),
                      const Sizer(width: 4),
                      Text(
                        errorText!,
                        style: Theme.of(context).textTheme.labelSmall?.copyWith(
                              color: ColorRes.anisErrorRed,
                            ),
                      ),
                    ],
                  ),
                )
              : const SizedBox.shrink(),
        ),
      ],
    );
  }
}

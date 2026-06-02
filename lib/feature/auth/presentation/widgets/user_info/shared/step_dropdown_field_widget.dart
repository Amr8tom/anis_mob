import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Generic dark dropdown used on wizard steps.
/// [T] is the item value type (typically String).
class StepDropdownField<T> extends StatelessWidget {
  final T? value;
  final String hint;
  final List<T> items;
  final ValueChanged<T?> onChanged;
  final IconData prefixIcon;

  const StepDropdownField({
    super.key,
    required this.value,
    required this.hint,
    required this.items,
    required this.onChanged,
    required this.prefixIcon,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorRes.anisAuthContainer,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: value != null
              ? ColorRes.anisGreen.withValues(alpha: 0.50)
              : ColorRes.anisAuthBorder,
          width: 1.5,
        ),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<T>(
          value: value,
          isExpanded: true,
          hint: Padding(
            padding: EdgeInsets.only(left: 8.w),
            child: Text(
              hint,
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: ColorRes.white.withValues(alpha: 0.28),
              ),
            ),
          ),
          icon: Icon(
            Icons.keyboard_arrow_down_rounded,
            color: ColorRes.anisGreen.withValues(alpha: 0.70),
          ),
          dropdownColor: ColorRes.anisAuthBgMid,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            color: ColorRes.white,
            fontWeight: FontWeight.w600,
          ),
          padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          items: items.map((item) {
            return DropdownMenuItem<T>(
              value: item,
              child: Row(
                children: [
                  Icon(prefixIcon, color: ColorRes.anisGreen, size: 16.sp),
                  const Sizer(width: 8),
                  Flexible(child: Text(item.toString())),
                ],
              ),
            );
          }).toList(),
          onChanged: onChanged,
        ),
      ),
    );
  }
}

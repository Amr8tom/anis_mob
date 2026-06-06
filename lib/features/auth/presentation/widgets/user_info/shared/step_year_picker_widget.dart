import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Horizontal wrap of selectable year-of-study chips.
class StepYearPicker extends StatelessWidget {
  final String selected;
  final List<String> years;
  final ValueChanged<String> onSelect;

  const StepYearPicker({
    super.key,
    required this.selected,
    required this.years,
    required this.onSelect,
  });

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 8.w,
      runSpacing: 8.h,
      children: years.map((year) {
        final isSel = selected == year;
        return GestureDetector(
          onTap: () => onSelect(year),
          child: AnimatedContainer(
            duration: const Duration(milliseconds: 200),
            padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 10.h),
            decoration: BoxDecoration(
              color: isSel
                  ? ColorRes.anisGreen.withValues(alpha: 0.22)
                  : ColorRes.anisAuthContainer,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
              border: Border.all(
                color: isSel ? ColorRes.anisGreen : ColorRes.anisAuthBorder,
                width: isSel ? 1.5 : 1,
              ),
            ),
            child: Text(
              year,
              style: Theme.of(context).textTheme.labelMedium?.copyWith(
                    fontWeight: isSel ? FontWeight.bold : FontWeight.normal,
                    color: isSel
                        ? ColorRes.anisGreen
                        : ColorRes.white.withValues(alpha: 0.65),
                  ),
            ),
          ),
        );
      }).toList(),
    );
  }
}

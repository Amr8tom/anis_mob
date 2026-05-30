import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';

class BuddyFilterChips extends StatelessWidget {
  final String activeChip;
  final ValueChanged<String> onChipSelected;

  const BuddyFilterChips({
    super.key,
    required this.activeChip,
    required this.onChipSelected,
  });

  @override
  Widget build(BuildContext context) {
    final chips = [
      _Chip(key: 'all', label: S.current.allFilter),
      _Chip(key: 'today', label: S.current.filterToday),
      _Chip(key: 'thisWeek', label: S.current.thisWeek),
      _Chip(key: 'availableNow', label: S.current.availableNow),
    ];

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm,
      ),
      child: Row(
        // RTL: "الكل" (All) on the right as the first chip
        textDirection: TextDirection.rtl,
        children: chips.map((chip) {
          final isActive = chip.key == activeChip;
          return GestureDetector(
            onTap: () => onChipSelected(chip.key),
            child: Container(
              margin: EdgeInsets.only(left: AppSizes.sm),
              padding: EdgeInsets.symmetric(
                horizontal: AppSizes.md,
                vertical: 6.h,
              ),
              decoration: BoxDecoration(
                color: isActive ? ColorRes.anisGreen : ColorRes.white,
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                border: Border.all(
                  color: isActive ? ColorRes.anisGreen : ColorRes.accent,
                  width: 1,
                ),
              ),
              child: Text(
                chip.label,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      fontWeight: FontWeight.w600,
                      color:
                          isActive ? ColorRes.white : ColorRes.anisChipText,
                      fontSize: 12.sp,
                    ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }
}

class _Chip {
  final String key;
  final String label;
  const _Chip({required this.key, required this.label});
}

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/colors.dart';

/// Reusable section label used across all user-info steps.
/// Shows a coloured icon badge + bold title.
class StepSectionLabel extends StatelessWidget {
  final IconData icon;
  final String title;

  const StepSectionLabel({
    super.key,
    required this.icon,
    required this.title,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          padding: const EdgeInsets.all(5),
          decoration: BoxDecoration(
            color: ColorRes.anisGreen.withValues(alpha: 0.15),
            borderRadius: BorderRadius.circular(7.r),
          ),
          child: Icon(icon, color: ColorRes.anisGreen, size: 15.sp),
        ),
        const Sizer(width: 8),
        Text(
          title,
          style: Theme.of(context).textTheme.labelMedium?.copyWith(
            fontWeight: FontWeight.w700,
            color: ColorRes.white,
          ),
        ),
      ],
    );
  }
}

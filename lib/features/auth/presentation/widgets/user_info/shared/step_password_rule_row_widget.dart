import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/colors.dart';

/// Single live password-rule indicator row (icon + label, green when met).
class StepPasswordRuleRow extends StatelessWidget {
  final String label;
  final bool met;

  const StepPasswordRuleRow({
    super.key,
    required this.label,
    required this.met,
  });

  @override
  Widget build(BuildContext context) {
    return AnimatedSwitcher(
      duration: const Duration(milliseconds: 250),
      child: Row(
        key: ValueKey(met),
        children: [
          Icon(
            met
                ? Icons.check_circle_rounded
                : Icons.radio_button_unchecked_rounded,
            color: met
                ? ColorRes.anisButtonGreen
                : ColorRes.white.withValues(alpha: 0.30),
            size: 15.sp,
          ),
          const Sizer(width: 7),
          Text(
            label,
            style: Theme.of(context).textTheme.labelSmall?.copyWith(
                  fontWeight: met ? FontWeight.w600 : FontWeight.w400,
                  color: met
                      ? ColorRes.anisButtonGreen
                      : ColorRes.white.withValues(alpha: 0.55),
                ),
          ),
        ],
      ),
    );
  }
}

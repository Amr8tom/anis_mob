import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import 'attendance_stat_ring.dart';

/// One of the four stat cards inside the attendance grid.
///
/// Layout:
///   ┌─────────────────────┐
///   │       ◯ ring        │   ← AttendanceStatRing
///   │      [value]        │   ← number inside
///   │      "ساعة"          │   ← unit label inside
///   │   [title text]      │
///   └─────────────────────┘
class AttendanceStatCard extends StatelessWidget {
  const AttendanceStatCard({
    super.key,
    required this.value,
    required this.title,
    required this.color,
    this.progress = 1.0,
  });

  /// The numeric value rendered inside the ring (e.g. `176`, `1.5`).
  final String value;

  /// Localized title shown under the ring.
  final String title;

  /// Accent color used for the ring + number text.
  final Color color;

  /// 0..1 — controls how much of the ring is filled.
  final double progress;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.padding,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(color: ColorRes.grey5, width: 1),
        boxShadow: [
          BoxShadow(
            color: color.withValues(alpha: 0.06),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          /// Ring + centered value/unit
          AttendanceStatRing(
            color: color,
            progress: progress,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  value,
                  style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                        color: color,
                        fontWeight: FontWeight.w800,
                      ),
                ),
                const Sizer(height: 2),
                Text(
                  S.current.hour,
                  style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        color: ColorRes.grey2,
                        fontSize: AppSizes.fontSizeSm * 0.85,
                      ),
                ),
              ],
            ),
          ),
          const Sizer(height: 16),

          /// Title
          Text(
            title,
            textAlign: TextAlign.center,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  color: ColorRes.black,
                  fontWeight: FontWeight.w700,
                  fontSize: AppSizes.fontSizeSm,
                ),
          ),
        ],
      ),
    );
  }
}

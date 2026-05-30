import 'package:flutter/material.dart';
import 'package:flutter_staggered_animations/flutter_staggered_animations.dart';

import '../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/extentions/navigation_extension.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../generated/l10n.dart';
import 'attendance_stat_card.dart';

/// "إحصائيات الحضور" section on the Home screen.
///
/// Renders the section header (title + "View all" link) and the 2×2 grid
/// of [AttendanceStatCard] tiles.
///
/// Note on data:
///   - Values + progress ratios are passed in as parameters with sensible
///     defaults so the widget is testable in isolation. Once the API
///     is hooked up, just pass the resolved values from the parent
///     `BlocBuilder<HomeCubit, HomeState>`.
class AttendanceStatsSection extends StatelessWidget {
  const AttendanceStatsSection({
    super.key,
    this.totalLateHours = 3,
    this.totalWorkHours = 176,
    this.totalEarlyDepartureHours = 1.5,
    this.totalOvertimeHours = 12,
    this.maxWorkHours = 200,
  });

  final num totalLateHours;
  final num totalWorkHours;
  final num totalEarlyDepartureHours;
  final num totalOvertimeHours;

  /// Used to normalize progress for the work-hours ring (e.g. 200 monthly).
  final num maxWorkHours;

  @override
  Widget build(BuildContext context) {
    /// Build the four cards once so we can wrap each in a staggered
    /// animation slot with a unique [position] index.
    final cards = <Widget>[
      AttendanceStatCard(
        value: _formatNumber(totalLateHours),
        title: S.current.totalLateHours,
        color: ColorRes.warning,
        progress: _safeRatio(totalLateHours, 8),
      ),
      AttendanceStatCard(
        value: _formatNumber(totalWorkHours),
        title: S.current.totalWorkHours,
        color: ColorRes.primary,
        progress: _safeRatio(totalWorkHours, maxWorkHours),
      ),
      AttendanceStatCard(
        value: _formatNumber(totalEarlyDepartureHours),
        title: S.current.totalEarlyDepartureHours,
        color: ColorRes.red,
        progress: _safeRatio(totalEarlyDepartureHours, 8),
      ),
      AttendanceStatCard(
        value: _formatNumber(totalOvertimeHours),
        title: S.current.totalOvertimeHours,
        color: ColorRes.success,
        progress: _safeRatio(totalOvertimeHours, 40),
      ),
    ];

    return AnimationLimiter(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          /// Section header
          _SectionHeader(
            title: S.current.attendanceStatistics,
            onViewAll: () =>
                context.pushNamed(DRoutesName.attendanceRoute),
          ),
          const Sizer(height: 12),

          /// 2×2 grid — `IntrinsicHeight` keeps the two cards in a row
          /// the same height even if one title wraps to two lines.
          /// Each card is wrapped in a staggered slide+fade so they
          /// fly in one-by-one when the screen first appears.
          IntrinsicHeight(
            child: Row(
              children: [
                Expanded(child: _animatedSlot(0, cards[0])),
                const Sizer(width: 12),
                Expanded(child: _animatedSlot(1, cards[1])),
              ],
            ),
          ),
          const Sizer(height: 12),
          IntrinsicHeight(
            child: Row(
              children: [
                Expanded(child: _animatedSlot(2, cards[2])),
                const Sizer(width: 12),
                Expanded(child: _animatedSlot(3, cards[3])),
              ],
            ),
          ),
        ],
      ),
    );
  }

  /// Wraps a stat card in a staggered grid animation so the four cards
  /// appear sequentially with a slide-up + fade-in effect.
  Widget _animatedSlot(int position, Widget child) {
    return AnimationConfiguration.staggeredGrid(
      position: position,
      columnCount: 2,
      duration: const Duration(milliseconds: 800),
      child: ScaleAnimation(
        scale: 0.95,
        child: FadeInAnimation(
          child: SlideAnimation(
            verticalOffset: 30,
            child: child,
          ),
        ),
      ),
    );
  }

  /// Shows whole numbers as `3` and fractional ones as `1.5`.
  String _formatNumber(num n) {
    if (n == n.toInt()) return n.toInt().toString();
    return n.toString();
  }

  double _safeRatio(num value, num max) {
    if (max <= 0) return 0;
    return (value / max).clamp(0.0, 1.0).toDouble();
  }
}

/// Section header row — "title" on the leading edge, "View all >" on
/// the trailing edge (RTL-aware via the natural Row direction).
class _SectionHeader extends StatelessWidget {
  const _SectionHeader({required this.title, required this.onViewAll});

  final String title;
  final VoidCallback onViewAll;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        /// "View all >" link (clickable)
        InkWell(
          onTap: onViewAll,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          child: Padding(
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.xs,
              vertical: AppSizes.xs,
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  Directionality.of(context) == TextDirection.rtl
                      ? Icons.arrow_back_ios_new_rounded
                      : Icons.arrow_forward_ios_rounded,
                  size: AppSizes.iconSm,
                  color: ColorRes.primary,
                ),
                const Sizer(width: 4),
                Text(
                  S.current.viewAll,
                  style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                        color: ColorRes.primary,
                        fontWeight: FontWeight.w700,
                        fontSize: AppSizes.fontSizeSm,
                      ),
                ),
              ],
            ),
          ),
        ),

        const Spacer(),

        /// Section title
        Text(
          title,
          style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                color: ColorRes.black,
                fontWeight: FontWeight.w800,
              ),
        ),
      ],
    );
  }
}

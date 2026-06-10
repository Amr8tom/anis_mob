import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import 'schedule_tile.dart';

class ScheduleCard extends StatelessWidget {
  final BuddySessionEntity session;
  const ScheduleCard({super.key, required this.session});

  String _formatDate(DateTime dt) {
    const months = [
      'January',
      'February',
      'March',
      'April',
      'May',
      'June',
      'July',
      'August',
      'September',
      'October',
      'November',
      'December',
    ];
    return '${dt.day} ${months[dt.month - 1]}, ${dt.year}';
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.05),
            blurRadius: AppSizes.sm + 2,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: IntrinsicHeight(
        child: Row(
          children: [
            Expanded(
              child: ScheduleTile(
                icon: Icons.schedule_rounded,
                iconBg: ColorRes.anisGreen.withValues(alpha: 0.1),
                iconColor: ColorRes.anisGreen,
                label: S.current.sessionTime,
                value: session.timeLabel,
              ),
            ),
            VerticalDivider(
              width: 1,
              thickness: 1,
              color: ColorRes.anisLine,
              indent: AppSizes.md,
              endIndent: AppSizes.md,
            ),
            Expanded(
              child: ScheduleTile(
                icon: Icons.calendar_today_rounded,
                iconBg: ColorRes.anisTagBlueTxt.withValues(alpha: 0.1),
                iconColor: ColorRes.anisTagBlueTxt,
                label: S.current.sessionDate,
                value: _formatDate(session.startTime),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

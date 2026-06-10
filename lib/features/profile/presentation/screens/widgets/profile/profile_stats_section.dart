import 'package:flutter/material.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/profile_entity.dart';

import 'stat_cell.dart';
import 'vert_divider.dart';

class ProfileStatsSection extends StatelessWidget {
  final ProfileEntity? profile;
  const ProfileStatsSection({super.key, required this.profile});

  @override
  Widget build(BuildContext context) {
    final hours = profile?.totalStudyHours ?? 0;
    final streak = profile?.streakDays ?? 0;
    final sessions = profile?.totalSessions ?? 0;

    return Padding(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.md,
      ),
      child: Row(
        children: [
          StatCell(value: '$hours', label: S.current.totalStudyHours),
          const VertDivider(),
          StatCell(value: '$streak', label: S.current.streakDays),
          const VertDivider(),
          StatCell(value: '$sessions', label: S.current.sessionsCount),
        ],
      ),
    );
  }
}

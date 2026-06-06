import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/profile_entity.dart';

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
          _StatCell(value: '$hours', label: S.current.totalStudyHours),
          _VertDivider(),
          _StatCell(value: '$streak', label: S.current.streakDays),
          _VertDivider(),
          _StatCell(value: '$sessions', label: S.current.sessionsCount),
        ],
      ),
    );
  }
}

class _StatCell extends StatelessWidget {
  final String value;
  final String label;
  const _StatCell({required this.value, required this.label});

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Column(
        children: [
          Text(
            value,
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
          ),
          const Sizer(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisHintText,
                ),
          ),
        ],
      ),
    );
  }
}

class _VertDivider extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: 1,
      height: AppSizes.containerSmall * 0.6,
      color: ColorRes.accent,
    );
  }
}

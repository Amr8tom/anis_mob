import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/profile_entity.dart';

class ProfileBadgesSection extends StatelessWidget {
  final List<ProfileBadge> badges;
  const ProfileBadgesSection({super.key, required this.badges});

  @override
  Widget build(BuildContext context) {
    if (badges.isEmpty) return const SizedBox.shrink();

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.badgesEarned,
            textAlign: TextAlign.start,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
          ),
          const Sizer(height: 12),
          Wrap(
            spacing: AppSizes.sm,
            runSpacing: AppSizes.sm,
            alignment: WrapAlignment.start,
            children: badges.map((b) => _BadgeChip(badge: b)).toList(),
          ),
          const Sizer(height: 8),
          Container(height: 1, color: ColorRes.accent.withOpacity(0.5)),
        ],
      ),
    );
  }
}

class _BadgeChip extends StatelessWidget {
  final ProfileBadge badge;
  const _BadgeChip({required this.badge});

  IconData _icon() {
    switch (badge.iconKey) {
      case 'streak':
        return Icons.local_fire_department_rounded;
      case 'hours':
        return Icons.timer_rounded;
      case 'top':
        return Icons.emoji_events_rounded;
      default:
        return Icons.star_rounded;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGreen.withOpacity(0.08),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisGreen.withOpacity(0.2),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(_icon(), size: AppSizes.iconXs, color: ColorRes.anisGreen),
          const Sizer(width: 5),
          Text(
            badge.label,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisGreen,
                  fontWeight: FontWeight.w600,
                ),
          ),
        ],
      ),
    );
  }
}

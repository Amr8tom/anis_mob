import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/profile_entity.dart';

class BadgeChip extends StatelessWidget {
  final ProfileBadge badge;
  const BadgeChip({super.key, required this.badge});

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
        color: ColorRes.anisGreen.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisGreen.withValues(alpha: 0.2),
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

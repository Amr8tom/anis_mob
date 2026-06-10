import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class SubscriptionBadge extends StatelessWidget {
  final String type;
  const SubscriptionBadge({super.key, required this.type});

  String _label() {
    switch (type) {
      case 'gold':
        return S.current.goldSubscription;
      case 'free':
        return S.current.freeSubscription;
      default:
        return S.current.silverSubscription;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.md,
        vertical: AppSizes.xs + 1,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGold.withValues(alpha: 0.2),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
            color: ColorRes.anisGold.withValues(alpha: 0.5), width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.star_rounded,
              size: AppSizes.iconXs, color: ColorRes.anisGold),
          const Sizer(width: 5),
          Text(
            _label(),
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisGold,
                  fontWeight: FontWeight.w700,
                ),
          ),
        ],
      ),
    );
  }
}

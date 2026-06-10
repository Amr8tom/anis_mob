import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class RuleChip extends StatelessWidget {
  final int index;
  final String text;
  final VoidCallback onRemove;

  const RuleChip({
    super.key,
    required this.index,
    required this.text,
    required this.onRemove,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.xs + 2),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisWarningBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(
            color: ColorRes.anisGold.withValues(alpha: 0.3), width: 1),
      ),
      child: Row(
        children: [
          Container(
            width: AppSizes.iconXs + 4,
            height: AppSizes.iconXs + 4,
            decoration: BoxDecoration(
              color: ColorRes.anisGold.withValues(alpha: 0.3),
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              '$index',
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.anisGold,
                    fontSize: 10,
                  ),
            ),
          ),
          const Sizer(width: 8),
          Expanded(
            child: Text(
              text,
              textAlign: TextAlign.start,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: ColorRes.anisTextDark,
                  ),
            ),
          ),
          GestureDetector(
            onTap: onRemove,
            child: Icon(Icons.close_rounded,
                size: AppSizes.iconXs + 2, color: ColorRes.anisHintText),
          ),
        ],
      ),
    );
  }
}

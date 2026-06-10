import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class RuleItem extends StatelessWidget {
  final int index;
  final String text;
  const RuleItem({super.key, required this.index, required this.text});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.04),
            blurRadius: AppSizes.sm - 2,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Number badge
          Container(
            width: AppSizes.iconSm + 6,
            height: AppSizes.iconSm + 6,
            decoration: const BoxDecoration(
              color: ColorRes.anisWarningBg,
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              '$index',
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisGold,
              ),
            ),
          ),
          const Sizer(width: 12),

          // Rule text
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(top: AppSizes.xs - 1),
              child: Text(
                text,
                textAlign: TextAlign.start,
                style: tt.bodyMedium?.copyWith(
                  color: ColorRes.anisTextDark,
                  height: 1.5,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

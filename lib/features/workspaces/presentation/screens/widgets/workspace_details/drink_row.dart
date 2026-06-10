import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/workspace_drink_entity.dart';

class DrinkRow extends StatelessWidget {
  final WorkspaceDrinkEntity drink;
  final String emoji;
  const DrinkRow({super.key, required this.drink, required this.emoji});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.sm),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.md,
        vertical: AppSizes.sm + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.04),
            blurRadius: 10,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      child: Row(
        children: [
          // Emoji icon container
          Container(
            width: 44.0,
            height: 44.0,
            decoration: BoxDecoration(
              color: ColorRes.anisCardBg,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            alignment: Alignment.center,
            child: Text(emoji, style: const TextStyle(fontSize: 22)),
          ),
          Sizer(width: AppSizes.sm),
          // Name
          Expanded(
            child: Text(
              drink.name,
              textAlign: TextAlign.start,
              style: tt.bodyMedium?.copyWith(
                fontWeight: FontWeight.w600,
                color: ColorRes.anisNavy,
              ),
            ),
          ),
          Sizer(width: AppSizes.sm),
          // Price badge
          Container(
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.sm + 2,
              vertical: AppSizes.xs,
            ),
            decoration: BoxDecoration(
              color: ColorRes.anisGreen.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            child: Text(
              '${drink.price.toStringAsFixed(0)} EGP',
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.anisGreen,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

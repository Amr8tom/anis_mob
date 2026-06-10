import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class EmptySlotCard extends StatelessWidget {
  const EmptySlotCard({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 110,
      decoration: BoxDecoration(
        color: ColorRes.anisChipBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisLine,
          width: 1.5,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            width: AppSizes.iconXLarge,
            height: AppSizes.iconXLarge,
            decoration: const BoxDecoration(
              color: ColorRes.anisLine,
              shape: BoxShape.circle,
            ),
            child: Icon(
              Icons.add_rounded,
              size: AppSizes.iconMd,
              color: ColorRes.anisHintText,
            ),
          ),
          const Sizer(height: 8),
          Text(
            S.current.openSpot,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisHintText,
                  fontSize: 11,
                ),
          ),
        ],
      ),
    );
  }
}

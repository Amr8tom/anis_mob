import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import 'plans_data.dart';

class PlansFeatureRow extends StatelessWidget {
  final PlanFeature feature;
  final Color accentColor;

  const PlansFeatureRow({
    super.key,
    required this.feature,
    required this.accentColor,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 4,
          height: AppSizes.iconSm + 4,
          decoration: BoxDecoration(
            color: feature.included
                ? accentColor.withValues(alpha: 0.1)
                : ColorRes.anisChipBg,
            shape: BoxShape.circle,
          ),
          child: Icon(
            feature.included ? Icons.check_rounded : Icons.close_rounded,
            size: AppSizes.iconXs,
            color: feature.included ? accentColor : ColorRes.anisHintText,
          ),
        ),
        const Sizer(width: 10),
        Expanded(
          child: Text(
            feature.label,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: feature.included
                      ? ColorRes.anisTextDark
                      : ColorRes.anisHintText,
                  fontWeight:
                      feature.included ? FontWeight.w500 : FontWeight.w400,
                ),
          ),
        ),
      ],
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import 'plans_data.dart';
import 'plans_feature_row.dart';
import 'plans_tag_chip.dart';

class PlansDetailCardSection extends StatelessWidget {
  final PlanData plan;
  final bool isCurrent;
  final VoidCallback? onChoosePlan;

  const PlansDetailCardSection({
    super.key,
    required this.plan,
    required this.isCurrent,
    this.onChoosePlan,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return SingleChildScrollView(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.xl,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(
            padding: EdgeInsets.all(AppSizes.md + 4),
            decoration: BoxDecoration(
              color: plan.bgColor,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              border: Border.all(
                color: plan.accentColor.withValues(alpha: 0.3),
                width: 1.5,
              ),
            ),
            child: Column(
              children: [
                if (plan.badge != null || isCurrent)
                  Padding(
                    padding: EdgeInsets.only(bottom: AppSizes.sm),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        if (plan.badge != null)
                          PlansTagChip(
                            label: plan.badge!,
                            color: plan.accentColor,
                          ),
                        if (isCurrent) ...[
                          if (plan.badge != null) const Sizer(width: 8),
                          PlansTagChip(
                            label: S.current.planCurrentBadge,
                            color: ColorRes.anisGreen,
                          ),
                        ],
                      ],
                    ),
                  ),
                Container(
                  width: AppSizes.iconXLarge * 1.5,
                  height: AppSizes.iconXLarge * 1.5,
                  decoration: BoxDecoration(
                    color: plan.accentColor.withValues(alpha: 0.12),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(
                    plan.icon,
                    size: AppSizes.iconLg,
                    color: plan.accentColor,
                  ),
                ),
                const Sizer(height: 12),
                Text(
                  plan.title,
                  style: tt.headlineMedium?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.anisNavy,
                  ),
                ),
                const Sizer(height: 4),
                Text(
                  plan.price,
                  style: tt.headlineSmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: plan.accentColor,
                  ),
                ),
              ],
            ),
          ),
          const Sizer(height: 16),
          Container(
            padding: EdgeInsets.all(AppSizes.md),
            decoration: BoxDecoration(
              color: ColorRes.white,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisNavy.withValues(alpha: 0.05),
                  blurRadius: AppSizes.md,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              children: plan.features.asMap().entries.map((entry) {
                final isLast = entry.key == plan.features.length - 1;
                return Column(
                  children: [
                    PlansFeatureRow(
                      feature: entry.value,
                      accentColor: plan.accentColor,
                    ),
                    if (!isLast)
                      Divider(
                        height: AppSizes.md,
                        color: ColorRes.anisLine,
                      ),
                  ],
                );
              }).toList(),
            ),
          ),
          const Sizer(height: 20),
          if (isCurrent)
            OutlinedButton(
              onPressed: null,
              style: OutlinedButton.styleFrom(
                disabledForegroundColor:
                    plan.accentColor.withValues(alpha: 0.6),
                side: BorderSide(
                  color: plan.accentColor.withValues(alpha: 0.4),
                  width: 1.5,
                ),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
              ),
              child: Text(
                S.current.planCurrentBadge,
                style: tt.bodyLarge?.copyWith(fontWeight: FontWeight.w700),
              ),
            )
          else
            ElevatedButton(
              onPressed: onChoosePlan,
              style: ElevatedButton.styleFrom(
                backgroundColor: plan.accentColor,
                foregroundColor: ColorRes.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
              ),
              child: Text(
                plan.ctaLabel,
                style: tt.bodyLarge?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

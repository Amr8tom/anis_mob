import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import 'plans_data.dart';

class PlansTabsSection extends StatelessWidget {
  final List<PlanData> plans;
  const PlansTabsSection({super.key, required this.plans});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.md,
        AppSizes.padding,
        AppSizes.sm,
      ),
      child: Container(
        padding: EdgeInsets.all(AppSizes.xs),
        decoration: BoxDecoration(
          color: ColorRes.anisLine,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        ),
        child: TabBar(
          isScrollable: false,
          indicatorSize: TabBarIndicatorSize.tab,
          dividerColor: Colors.transparent,
          labelPadding: EdgeInsets.zero,
          indicator: BoxDecoration(
            color: ColorRes.white,
            borderRadius: BorderRadius.circular(
              AppSizes.borderRadiusXLg - AppSizes.xs,
            ),
          ),
          tabs: plans
              .map(
                (plan) => Tab(
                  child: _PlanTabItem(plan: plan, textTheme: tt),
                ),
              )
              .toList(),
        ),
      ),
    );
  }
}

class _PlanTabItem extends StatelessWidget {
  final PlanData plan;
  final TextTheme textTheme;

  const _PlanTabItem({required this.plan, required this.textTheme});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 42,
      child: Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(plan.icon, size: AppSizes.iconSm, color: plan.accentColor),
            const Sizer(height: 1),
            Text(
              plan.title,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: textTheme.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: plan.accentColor,
                height: 1,
              ),
            ),
          ],
        ),
      ),
    );
  }
}



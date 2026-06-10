import 'package:flutter/material.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import 'plans_data.dart';

import 'plan_tab_item.dart';

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
                  child: PlanTabItem(plan: plan, textTheme: tt),
                ),
              )
              .toList(),
        ),
      ),
    );
  }
}

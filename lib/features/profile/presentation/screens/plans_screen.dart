import 'package:flutter/material.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import 'widgets/plans/plans_data.dart';
import 'widgets/plans/plans_detail_card_section.dart';
import 'widgets/plans/plans_tabs_section.dart';

class PlansScreen extends StatelessWidget {
  final String currentPlan;
  const PlansScreen({super.key, required this.currentPlan});

  @override
  Widget build(BuildContext context) {
    final plans = buildPlans();
    final initialIndex = plans.indexWhere((p) => p.key == currentPlan);
    final index = initialIndex == -1 ? 0 : initialIndex;

    return DefaultTabController(
      length: plans.length,
      initialIndex: index,
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        appBar: DAppBar(
          showBackArrow: true,
          title: S.current.choosePlanTitle,
        ),
        body: Column(
          children: [
            PlansTabsSection(plans: plans),
            Expanded(
              child: TabBarView(
                children: plans
                    .map(
                      (plan) => PlansDetailCardSection(
                        plan: plan,
                        isCurrent: plan.key == currentPlan,
                      ),
                    )
                    .toList(),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

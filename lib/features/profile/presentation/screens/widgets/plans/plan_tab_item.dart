import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import 'plans_data.dart';

class PlanTabItem extends StatelessWidget {
  final PlanData plan;
  final TextTheme textTheme;

  const PlanTabItem({
    super.key,
    required this.plan,
    required this.textTheme,
  });

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

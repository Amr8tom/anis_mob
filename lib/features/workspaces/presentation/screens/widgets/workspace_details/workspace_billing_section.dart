import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

class WorkspaceBillingSection extends StatelessWidget {
  final WorkspaceEntity workspace;
  const WorkspaceBillingSection({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final multiplier = workspace.hourMultiplier;
    final capHours =
        (workspace.dayCalculationHours * multiplier).toStringAsFixed(0);
    final realHours = workspace.dayCalculationHours.toString();

    String multiplierText;
    if (multiplier == 1.0) {
      multiplierText = S.current.workspaceHourMultiplierStandard;
    } else if (multiplier == 2.0) {
      multiplierText = S.current.workspaceHourMultiplierPremium;
    } else if (multiplier == 0.0) {
      multiplierText = S.current.workspaceHourMultiplierFree;
    } else {
      multiplierText = S.current
          .workspaceHourMultiplierCustom(multiplier.toStringAsFixed(1));
    }

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.anisGold.withValues(alpha: 0.12),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: ColorRes.anisGold.withValues(alpha: 0.35),
          ),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 38,
              height: 38,
              decoration: BoxDecoration(
                color: ColorRes.anisGold.withValues(alpha: 0.18),
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.timer_outlined,
                color: ColorRes.anisGold,
                size: 20,
              ),
            ),
            Sizer(width: AppSizes.sm),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    S.current.workspaceDayCalculationTitle,
                    style: tt.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: ColorRes.anisNavy,
                    ),
                  ),
                  Sizer(height: AppSizes.xs),
                  Text(
                    multiplierText,
                    style: tt.bodySmall?.copyWith(
                      fontWeight: FontWeight.w600,
                      color: ColorRes.anisNavy,
                      height: 1.5,
                    ),
                  ),
                  if (multiplier > 0.0) ...[
                    Sizer(height: AppSizes.xs),
                    Text(
                      S.current.workspaceDailyCapText(capHours, realHours),
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisTextMuted,
                        height: 1.5,
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

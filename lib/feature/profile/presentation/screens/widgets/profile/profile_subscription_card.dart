import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/profile_entity.dart';
import '../../../controller/profile_cubit.dart';

class ProfileSubscriptionCard extends StatelessWidget {
  final ProfileEntity? profile;
  const ProfileSubscriptionCard({super.key, required this.profile});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final subType = profile?.subscriptionType ?? 'free';
    final daysLeft = profile?.subscriptionDaysRemaining ?? 0;
    final isGold = subType == 'gold';

    final planLabel = isGold
        ? S.current.goldSubscription
        : subType == 'silver'
            ? S.current.silverSubscription
            : S.current.freeSubscription;

    final planColor = isGold
        ? ColorRes.anisGold
        : subType == 'silver'
            ? ColorRes.anisTextMuted
            : ColorRes.anisGreen;

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.sm,
      ),
      child: Container(
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.07),
              blurRadius: AppSizes.md,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        padding: EdgeInsets.all(AppSizes.md + 2),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ── Plan name row ─────────────────────────────────
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // Current plan label
                Text(
                  S.current.currentPlan,
                  style: tt.bodyMedium?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisNavy,
                  ),
                ),
                // Plan badge
                Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: AppSizes.sm + 2,
                    vertical: AppSizes.xs,
                  ),
                  decoration: BoxDecoration(
                    color: planColor.withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                    border: Border.all(
                      color: planColor.withValues(alpha: 0.35),
                      width: 1,
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.star_rounded,
                          size: AppSizes.iconXs, color: planColor),
                      const Sizer(width: 4),
                      Text(
                        planLabel,
                        style: tt.bodySmall?.copyWith(
                          color: planColor,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),

            const Sizer(height: 14),
            Container(height: 1, color: ColorRes.accent.withValues(alpha: 0.5)),
            const Sizer(height: 14),

            // ── Days remaining ────────────────────────────────
            Row(
              children: [
                Icon(Icons.access_time_rounded,
                    size: AppSizes.iconSm, color: ColorRes.anisGreen),
                const Sizer(width: 6),
                Text(
                  S.current.daysLeft(daysLeft),
                  style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
                ),
              ],
            ),

            const Sizer(height: 16),

            // ── Action button ──────────────────────────────────
            SizedBox(
              width: double.infinity,
              child: isGold
                  ? OutlinedButton(
                      onPressed: () =>
                          context.read<ProfileCubit>().navigateToPlans(context),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: ColorRes.anisGreen,
                        side: const BorderSide(
                          color: ColorRes.anisGreen,
                          width: 1.5,
                        ),
                        shape: RoundedRectangleBorder(
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusXLg),
                        ),
                        padding:
                            EdgeInsets.symmetric(vertical: AppSizes.sm + 4),
                      ),
                      child: Text(
                        S.current.manageSubscription,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.anisGreen,
                        ),
                      ),
                    )
                  : ElevatedButton(
                      onPressed: () =>
                          context.read<ProfileCubit>().navigateToPlans(context),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: ColorRes.anisGreen,
                        foregroundColor: ColorRes.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusXLg),
                        ),
                        padding:
                            EdgeInsets.symmetric(vertical: AppSizes.sm + 4),
                      ),
                      child: Text(
                        S.current.upgradePlan,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.white,
                        ),
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class SessionGiftSection extends StatelessWidget {
  final String? gift;
  const SessionGiftSection({super.key, required this.gift});

  @override
  Widget build(BuildContext context) {
    if (gift == null || gift!.isEmpty) return const SizedBox.shrink();
    final tt = Theme.of(context).textTheme;

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [
              ColorRes.anisGold.withOpacity(0.09),
              ColorRes.anisGreen.withOpacity(0.05),
            ],
            begin: AlignmentDirectional.topStart,
            end: AlignmentDirectional.bottomEnd,
          ),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: Border.all(
            color: ColorRes.anisGold.withOpacity(0.3),
            width: 1,
          ),
        ),
        padding: EdgeInsets.all(AppSizes.md),
        child: Row(
          children: [
            // Gift icon
            Container(
              width: AppSizes.iconXLarge + 4,
              height: AppSizes.iconXLarge + 4,
              decoration: BoxDecoration(
                color: ColorRes.anisGold.withOpacity(0.12),
                borderRadius:
                    BorderRadius.circular(AppSizes.borderRadiusLg),
              ),
              child: Icon(
                Icons.card_giftcard_rounded,
                size: AppSizes.iconMd,
                color: ColorRes.anisGold,
              ),
            ),
            const Sizer(width: 12),

            // Gift label + text
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    S.current.sessionGift,
                    style: tt.bodySmall?.copyWith(
                      color: ColorRes.anisGold,
                      fontWeight: FontWeight.w700,
                      letterSpacing: 0.2,
                    ),
                  ),
                  const Sizer(height: 3),
                  Text(
                    gift!,
                    textAlign: TextAlign.start,
                    style: tt.bodyMedium?.copyWith(
                      color: ColorRes.anisTextDark,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

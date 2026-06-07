import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class ProfileCompletionHeader extends StatelessWidget {
  final int percentage;
  final VoidCallback? onSkip;

  const ProfileCompletionHeader({
    super.key,
    required this.percentage,
    required this.onSkip,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      width: double.infinity,
      color: ColorRes.anisGreen,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Align(
            alignment: AlignmentDirectional.centerEnd,
            child: TextButton(
              onPressed: onSkip,
              child: Text(
                S.current.doItLater,
                style: tt.bodyMedium?.copyWith(
                  color: ColorRes.white.withValues(alpha: 0.82),
                  fontWeight: FontWeight.w700,
                  overflow: TextOverflow.visible,
                ),
              ),
            ),
          ),
          Container(
            width: 46,
            height: 46,
            decoration: BoxDecoration(
              color: ColorRes.white.withValues(alpha: 0.16),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.auto_awesome_rounded,
              color: ColorRes.white,
              size: 24,
            ),
          ),
          const Sizer(height: 12),
          Text(
            S.current.completeProfileTitle,
            style: tt.headlineSmall?.copyWith(
              color: ColorRes.white,
              fontWeight: FontWeight.w800,
              overflow: TextOverflow.visible,
            ),
          ),
          const Sizer(height: 4),
          Text(
            S.current.completeProfileSubtitle,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.78),
              height: 1.5,
              overflow: TextOverflow.visible,
            ),
          ),
          const Sizer(height: 14),
          ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
            child: LinearProgressIndicator(
              value: percentage / 100,
              minHeight: 5,
              backgroundColor: ColorRes.white.withValues(alpha: 0.2),
              valueColor: const AlwaysStoppedAnimation(ColorRes.white),
            ),
          ),
        ],
      ),
    );
  }
}

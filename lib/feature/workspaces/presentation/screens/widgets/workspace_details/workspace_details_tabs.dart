import 'package:flutter/material.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceDetailsTabs extends StatelessWidget {
  const WorkspaceDetailsTabs({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm,
      ),
      decoration: BoxDecoration(
        color: ColorRes.accent.withValues(alpha: 0.35),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
      ),
      child: TabBar(
        labelColor: ColorRes.white,
        unselectedLabelColor: ColorRes.anisChipText,
        indicator: BoxDecoration(
          color: ColorRes.anisGreen,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        ),
        tabs: [
          Tab(text: S.current.gallery),
          Tab(text: S.current.price),
        ],
        labelStyle: tt.bodyMedium?.copyWith(fontWeight: FontWeight.w700),
        unselectedLabelStyle:
            tt.bodyMedium?.copyWith(fontWeight: FontWeight.w600),
      ),
    );
  }
}



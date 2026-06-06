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
        color: ColorRes.anisChipBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
      ),
      child: TabBar(
        labelColor: ColorRes.white,
        unselectedLabelColor: ColorRes.anisChipText,
        indicator: BoxDecoration(
          color: ColorRes.anisGreen,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        ),
        indicatorSize: TabBarIndicatorSize.tab,
        dividerColor: Colors.transparent,
        tabs: [
          Tab(
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.groups_2_outlined, size: 16),
                SizedBox(width: AppSizes.xs),
                Text(S.current.sessionsTab),
              ],
            ),
          ),
          Tab(
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.info_outline_rounded, size: 16),
                SizedBox(width: AppSizes.xs),
                Text(S.current.infoTab),
              ],
            ),
          ),
        ],
        labelStyle: tt.bodySmall?.copyWith(fontWeight: FontWeight.w700),
        unselectedLabelStyle: tt.bodySmall?.copyWith(fontWeight: FontWeight.w600),
      ),
    );
  }
}

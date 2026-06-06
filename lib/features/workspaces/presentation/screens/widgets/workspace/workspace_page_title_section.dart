import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspacePageTitleSection extends StatelessWidget {
  const WorkspacePageTitleSection({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return SliverToBoxAdapter(
      child: Padding(
        padding: EdgeInsets.fromLTRB(
          AppSizes.padding,
          AppSizes.md,
          AppSizes.padding,
          AppSizes.sm,
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              S.current.workspacesTab,
              textAlign: TextAlign.start,
              style: tt.headlineMedium?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisNavy,
              ),
            ),
            const Sizer(height: 4),
            Text(
              S.current.workspacesSubtitle,
              textAlign: TextAlign.start,
              style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
            ),
          ],
        ),
      ),
    );
  }
}

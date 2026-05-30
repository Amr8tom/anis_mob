import 'package:anis/feature/workspaces/presentation/screens/widgets/workspace/workspace_card_footer.dart';
import 'package:anis/feature/workspaces/presentation/screens/widgets/workspace/workspace_card_header.dart';
import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/workspace_entity.dart';

import 'workspace_occupancy_section.dart';

class WorkspaceCard extends StatelessWidget {
  final WorkspaceEntity workspace;
  final VoidCallback? onCheckIn;
  final VoidCallback? onTap;

  const WorkspaceCard({
    super.key,
    required this.workspace,
    this.onCheckIn,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.symmetric(
          horizontal: AppSizes.padding,
          vertical: AppSizes.sm * 0.6,
        ),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.07),
              blurRadius: 16,
              offset: const Offset(0, 4),
            ),
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.03),
              blurRadius: 4,
              offset: const Offset(0, 1),
            ),
          ],
        ),
        child: Padding(
          padding: EdgeInsets.all(AppSizes.spaceBetweenIcon + AppSizes.xs),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              WorkspaceCardHeader(workspace: workspace),
              const Sizer(height: 14),
              WorkspaceOccupancySection(workspace: workspace),
              const Sizer(height: 12),
              Container(
                height: 1,
                color: ColorRes.accent.withValues(alpha: 0.5),
              ),
              const Sizer(height: 12),
              WorkspaceCardFooter(workspace: workspace, onCheckIn: onCheckIn),
            ],
          ),
        ),
      ),
    );
  }
}

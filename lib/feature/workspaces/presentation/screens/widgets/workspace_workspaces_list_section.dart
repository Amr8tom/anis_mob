import 'package:flutter/material.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../domain/entity/workspace_entity.dart';
import 'workspace_card.dart';

class WorkspaceWorkspacesListSection extends StatelessWidget {
  final List<WorkspaceEntity> workspaces;
  final ValueChanged<WorkspaceEntity> onCheckIn;

  const WorkspaceWorkspacesListSection({
    super.key,
    required this.workspaces,
    required this.onCheckIn,
  });

  @override
  Widget build(BuildContext context) {
    return SliverPadding(
      padding: EdgeInsets.only(top: AppSizes.md),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (_, i) => WorkspaceCard(
            workspace: workspaces[i],
            onCheckIn: () => onCheckIn(workspaces[i]),
          ),
          childCount: workspaces.length,
        ),
      ),
    );
  }
}


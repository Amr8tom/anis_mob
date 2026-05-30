import 'package:anis/feature/workspaces/presentation/screens/widgets/workspace_details/workspace_drinks_tab.dart';
import 'package:flutter/material.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../domain/entity/workspace_entity.dart';
import 'widgets/workspace_details/workspace_details_tabs.dart';
import 'widgets/workspace_details/workspace_overview_tab.dart';

class WorkspaceDetailsScreen extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceDetailsScreen({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorRes.white,
      appBar: DAppBar(
        title: workspace.name,
        showBackArrow: true,
        fontSize: AppSizes.fontSizeMd,
      ),
      body: DefaultTabController(
        length: 2,
        child: Column(
          children: [
            const WorkspaceDetailsTabs(),
            Expanded(
              child: TabBarView(
                children: [
                  WorkspaceOverviewTab(workspace: workspace),
                  WorkspaceDrinksTab(workspace: workspace),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}



import 'package:flutter/material.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../domain/entity/workspace_entity.dart';
import 'widgets/workspace_details/workspace_details_tabs.dart';
import 'widgets/workspace_details/workspace_header_section.dart';
import 'widgets/workspace_details/workspace_info_tab.dart';
import 'widgets/workspace_details/workspace_sessions_tab.dart';

class WorkspaceDetailsScreen extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceDetailsScreen({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        appBar: DAppBar(
          title: workspace.name,
          showBackArrow: true,
          fontSize: AppSizes.fontSizeMd,
        ),
        body: Column(
          children: [
            // ── Info header (status, meta, occupancy) ──────────────
            WorkspaceHeaderSection(workspace: workspace),

            // ── Tab bar ────────────────────────────────────────────
            const WorkspaceDetailsTabs(),

            // ── Tab content ────────────────────────────────────────
            Expanded(
              child: TabBarView(
                physics: const NeverScrollableScrollPhysics(),
                children: [
                  WorkspaceSessionsTab(workspace: workspace),
                  WorkspaceInfoTab(workspace: workspace),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

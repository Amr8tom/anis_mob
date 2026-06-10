import 'package:flutter/material.dart';

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../core/extentions/navigation_extension.dart';
import '../../../../../../core/routing/route_names.dart';
import '../../../../../../features/home/domain/entity/study_session_entity.dart';
import '../../../../../../features/home/presentation/widgets/session_card_widget.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

import 'section_header.dart';

class WorkspaceSessionsTab extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceSessionsTab({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    final sessions = workspace.sessions;

    if (sessions.isEmpty) {
      return CustomUI.anisEmptyState(
        context: context,
        icon: Icons.groups_2_outlined,
        title: S.current.noSessionsInWorkspace,
        subtitle: S.current.beFirstToStartSession,
      );
    }

    final liveSessions =
        sessions.where((s) => s.status == SessionStatus.inProgress).toList();
    final upcomingSessions =
        sessions.where((s) => s.status == SessionStatus.upcoming).toList();

    return ListView(
      physics: const BouncingScrollPhysics(),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.md,
      ),
      children: [
        if (liveSessions.isNotEmpty) ...[
          SectionHeader(
            label: S.current.liveNow,
            color: ColorRes.anisGreen,
            icon: Icons.radio_button_checked_rounded,
          ),
          Sizer(height: AppSizes.sm),
          ...liveSessions.map(
            (s) => Padding(
              padding: EdgeInsets.only(bottom: AppSizes.sm),
              child: SessionCardWidget(
                session: s,
                onTap: () => _openSession(context, s.id),
              ),
            ),
          ),
          Sizer(height: AppSizes.md),
        ],
        if (upcomingSessions.isNotEmpty) ...[
          SectionHeader(
            label: S.current.upcomingSessions,
            color: ColorRes.anisTagBlueTxt,
            icon: Icons.schedule_rounded,
          ),
          Sizer(height: AppSizes.sm),
          ...upcomingSessions.map(
            (s) => Padding(
              padding: EdgeInsets.only(bottom: AppSizes.sm),
              child: SessionCardWidget(
                session: s,
                onTap: () => _openSession(context, s.id),
              ),
            ),
          ),
        ],
      ],
    );
  }

  void _openSession(BuildContext context, String sessionId) {
    context.pushNamed(
      DRoutesName.sessionDetailsRoute,
      arguments: {'sessionId': sessionId},
    );
  }
}

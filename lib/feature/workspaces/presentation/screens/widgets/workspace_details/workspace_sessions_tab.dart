import 'package:flutter/material.dart';

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../feature/home/domain/entity/study_session_entity.dart';
import '../../../../../../feature/home/presentation/widgets/session_card_widget.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

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
          _SectionHeader(
            label: S.current.liveNow,
            color: ColorRes.anisGreen,
            icon: Icons.radio_button_checked_rounded,
          ),
          Sizer(height: AppSizes.sm),
          ...liveSessions.map(
            (s) => Padding(
              padding: EdgeInsets.only(bottom: AppSizes.sm),
              child: SessionCardWidget(session: s),
            ),
          ),
          Sizer(height: AppSizes.md),
        ],
        if (upcomingSessions.isNotEmpty) ...[
          _SectionHeader(
            label: S.current.upcomingSessions,
            color: ColorRes.anisTagBlueTxt,
            icon: Icons.schedule_rounded,
          ),
          Sizer(height: AppSizes.sm),
          ...upcomingSessions.map(
            (s) => Padding(
              padding: EdgeInsets.only(bottom: AppSizes.sm),
              child: SessionCardWidget(session: s),
            ),
          ),
        ],
      ],
    );
  }
}

class _SectionHeader extends StatelessWidget {
  final String label;
  final Color color;
  final IconData icon;

  const _SectionHeader({
    required this.label,
    required this.color,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Row(
      children: [
        Icon(icon, size: AppSizes.iconSm, color: color),
        Sizer(width: AppSizes.xs),
        Text(
          label,
          style: tt.titleSmall?.copyWith(
            fontWeight: FontWeight.w700,
            color: color,
          ),
        ),
      ],
    );
  }
}

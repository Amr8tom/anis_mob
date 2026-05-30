import 'package:flutter/material.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/entity/workspace_entity.dart';

class WorkspaceStatusBadge extends StatelessWidget {
  final WorkspaceStatus status;

  const WorkspaceStatusBadge({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    final (label, bg, fg) = switch (status) {
      WorkspaceStatus.open => (
          S.current.workspaceOpen,
          ColorRes.anisTagGreen,
          ColorRes.anisGreen,
        ),
      WorkspaceStatus.busy => (
          S.current.workspaceBusy,
          ColorRes.anisWarningBg,
          ColorRes.anisBusyAmber,
        ),
      WorkspaceStatus.full => (
          'ممتلئ',
          ColorRes.anisErrorRedBg,
          ColorRes.anisErrorRed,
        ),
      WorkspaceStatus.closed => (
          S.current.closedNow,
          ColorRes.accent,
          ColorRes.anisHintText,
        ),
    };

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
      ),
      child: Text(
        label,
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: fg,
              fontSize: 11,
            ),
      ),
    );
  }
}


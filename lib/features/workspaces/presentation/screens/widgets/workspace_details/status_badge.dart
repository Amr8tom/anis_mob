import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

class StatusBadge extends StatelessWidget {
  final WorkspaceStatus status;
  const StatusBadge({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final (label, bg, fg) = switch (status) {
      WorkspaceStatus.open => (
          S.current.workspaceOpen,
          ColorRes.anisTagGreen,
          ColorRes.anisTagGreenTxt
        ),
      WorkspaceStatus.busy => (
          S.current.workspaceBusy,
          ColorRes.anisTagYellow,
          ColorRes.anisTagYellowTxt
        ),
      WorkspaceStatus.full => (
          S.current.workspaceFull,
          ColorRes.anisTagPink,
          ColorRes.anisTagPinkTxt
        ),
      WorkspaceStatus.closed => (
          S.current.closedNow,
          ColorRes.accent,
          ColorRes.anisChipText
        ),
    };

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
      ),
      child: Text(
        label,
        style: tt.bodySmall?.copyWith(
          fontWeight: FontWeight.w700,
          color: fg,
        ),
      ),
    );
  }
}

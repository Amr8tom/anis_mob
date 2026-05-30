import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

/// Compact info strip shown between the AppBar and the TabBar:
/// address · open/close time · distance · occupancy bar
class WorkspaceHeaderSection extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceHeaderSection({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm,
      ),
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.05),
            blurRadius: 12,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Status badge + address row
          Row(
            children: [
              _StatusBadge(status: workspace.status),
              Sizer(width: AppSizes.sm),
              Expanded(
                child: Text(
                  workspace.address,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
                ),
              ),
            ],
          ),
          Sizer(height: AppSizes.sm),

          // Meta row: open time · close time · distance
          Row(
            children: [
              _MetaItem(
                icon: Icons.access_time_rounded,
                label: '${workspace.openTime} – ${workspace.closeTime}',
              ),
              Sizer(width: AppSizes.md),
              _MetaItem(
                icon: Icons.near_me_rounded,
                label: '${workspace.distanceKm.toStringAsFixed(1)} ${S.current.km}',
              ),
              Sizer(width: AppSizes.md),
              _MetaItem(
                icon: Icons.people_outline_rounded,
                label: '${workspace.currentOccupancy}/${workspace.capacity}',
              ),
            ],
          ),
          Sizer(height: AppSizes.sm),

          // Occupancy progress bar
          ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
            child: LinearProgressIndicator(
              value: workspace.occupancyFraction,
              minHeight: 6,
              backgroundColor: ColorRes.anisLine,
              valueColor: AlwaysStoppedAnimation<Color>(
                _occupancyColor(workspace.occupancyFraction),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Color _occupancyColor(double fraction) {
    if (fraction >= 1.0) return ColorRes.error;
    if (fraction >= 0.8) return ColorRes.anisBusyAmber;
    return ColorRes.anisGreen;
  }
}

class _StatusBadge extends StatelessWidget {
  final WorkspaceStatus status;
  const _StatusBadge({required this.status});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final (label, bg, fg) = switch (status) {
      WorkspaceStatus.open => (S.current.workspaceOpen, ColorRes.anisTagGreen, ColorRes.anisTagGreenTxt),
      WorkspaceStatus.busy => (S.current.workspaceBusy, ColorRes.anisTagYellow, ColorRes.anisTagYellowTxt),
      WorkspaceStatus.full => (S.current.workspaceFull, ColorRes.anisTagPink, ColorRes.anisTagPinkTxt),
      WorkspaceStatus.closed => (S.current.closedNow, ColorRes.accent, ColorRes.anisChipText),
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

class _MetaItem extends StatelessWidget {
  final IconData icon;
  final String label;
  const _MetaItem({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: ColorRes.anisHintText),
        Sizer(width: 3),
        Text(
          label,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
        ),
      ],
    );
  }
}

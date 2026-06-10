import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

import 'status_badge.dart';
import 'meta_item.dart';

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
              StatusBadge(status: workspace.status),
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
              MetaItem(
                icon: Icons.access_time_rounded,
                label: '${workspace.openTime} – ${workspace.closeTime}',
              ),
              Sizer(width: AppSizes.md),
              MetaItem(
                icon: Icons.near_me_rounded,
                label:
                    '${workspace.distanceKm.toStringAsFixed(1)} ${S.current.km}',
              ),
              Sizer(width: AppSizes.md),
              MetaItem(
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

import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

class WorkspaceOccupancySection extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceOccupancySection({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Row(
          textDirection: TextDirection.rtl,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              S.current.workspaceCapacity,
              style: tt.bodySmall?.copyWith(
                color: ColorRes.anisTextMuted,
              ),
            ),
            Text(
              '${workspace.currentOccupancy}/${workspace.capacity}',
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: _occupancyColor(workspace.occupancyFraction),
              ),
            ),
          ],
        ),
        const Sizer(height: 6),
        ClipRRect(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
          child: LinearProgressIndicator(
            value: workspace.occupancyFraction,
            minHeight: 5,
            backgroundColor: ColorRes.accent,
            valueColor: AlwaysStoppedAnimation<Color>(
              _occupancyColor(workspace.occupancyFraction),
            ),
          ),
        ),
      ],
    );
  }

  Color _occupancyColor(double fraction) {
    if (fraction < 0.6) return ColorRes.anisOnlineGreen;
    if (fraction < 0.9) return ColorRes.anisBusyAmber;
    return ColorRes.anisErrorRed;
  }
}



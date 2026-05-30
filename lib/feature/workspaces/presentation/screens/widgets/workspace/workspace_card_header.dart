import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_status_badge.dart';

class WorkspaceCardHeader extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceCardHeader({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                workspace.name,
                textAlign: TextAlign.start,
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: tt.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.anisNavy,
                ),
              ),
              const Sizer(height: 3),
              Row(
                children: [
                  Icon(
                    Icons.location_on_outlined,
                    size: AppSizes.iconXs,
                    color: ColorRes.anisHintText,
                  ),
                  const Sizer(width: 2),
                  Flexible(
                    child: Text(
                      workspace.address,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisHintText,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
        const Sizer(width: 10),
        WorkspaceStatusBadge(status: workspace.status),
      ],
    );
  }
}


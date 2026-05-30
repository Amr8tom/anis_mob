import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_meta_item.dart';
import 'workspace_check_in_button.dart';
import 'workspace_closed_label.dart';
import 'workspace_meta_item.dart';

class WorkspaceCardFooter extends StatelessWidget {
  final WorkspaceEntity workspace;
  final VoidCallback? onCheckIn;

  const WorkspaceCardFooter({
    super.key,
    required this.workspace,
    this.onCheckIn,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        WorkspaceMetaItem(
          icon: Icons.schedule_rounded,
          label: '${workspace.openTime} - ${workspace.closeTime}',
        ),
        const Sizer(width: 12),
        WorkspaceMetaItem(
          icon: Icons.near_me_outlined,
          label: '${workspace.distanceKm} ${S.current.km}',
        ),
        const Spacer(),
        if (workspace.isOpen)
          WorkspaceCheckInButton(onTap: onCheckIn)
        else
          WorkspaceClosedLabel(status: workspace.status),
      ],
    );
  }
}


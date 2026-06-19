import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_entity.dart';

class WorkspaceClosedLabel extends StatelessWidget {
  final WorkspaceStatus status;

  const WorkspaceClosedLabel({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    final label = status == WorkspaceStatus.full ? S.current.workspaceFull : S.current.closedNow;
    return Text(
      label,
      style: Theme.of(context).textTheme.bodySmall?.copyWith(
            color: ColorRes.anisHintText,
            fontWeight: FontWeight.w600,
          ),
    );
  }
}


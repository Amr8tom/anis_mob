import 'package:flutter/material.dart';

import '../../../../../../common/custom_ui.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceEmptySection extends StatelessWidget {
  const WorkspaceEmptySection({super.key});

  @override
  Widget build(BuildContext context) {
    return SliverFillRemaining(
      child: CustomUI.appEmptyState(
        context: context,
        icon: Icons.store_outlined,
        title: S.current.noWorkspacesFound,
      ),
    );
  }
}


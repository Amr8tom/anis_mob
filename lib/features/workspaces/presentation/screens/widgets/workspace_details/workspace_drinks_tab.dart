import 'package:anis/features/workspaces/presentation/screens/widgets/workspace_details/workspace_drinks_list_section.dart';
import 'package:flutter/material.dart';

import '../../../../domain/entity/workspace_entity.dart';

class WorkspaceDrinksTab extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceDrinksTab({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return WorkspaceDrinksListSection(drinks: workspace.drinks);
  }
}

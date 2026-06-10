import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../../workspaces/domain/entity/workspace_entity.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';
import 'workspace_tile.dart';

/// Section 3 — searchable workspace picker backed by [WorkspaceEntity] data
/// fetched from the workspaces repository (same dummy data workspaces feature uses).
class CreateSessionWorkspaceSection extends StatefulWidget {
  final TextEditingController workspaceSearchCtrl;

  const CreateSessionWorkspaceSection({
    super.key,
    required this.workspaceSearchCtrl,
  });

  @override
  State<CreateSessionWorkspaceSection> createState() =>
      _CreateSessionWorkspaceSectionState();
}

class _CreateSessionWorkspaceSectionState
    extends State<CreateSessionWorkspaceSection> {
  @override
  void initState() {
    super.initState();
    // Trigger a workspace load on first build if not already loaded.
    final cubit = context.read<BuddyCubit>();
    if (cubit.state.workspacesStatus == BuddyStatus.initial) {
      cubit.loadWorkspaces();
    }
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.location),
        const Sizer(height: 10),
        BlocBuilder<BuddyCubit, BuddyState>(
          builder: (context, state) {
            return SessionFormCard(
              children: [
                SessionFieldLabel(S.current.selectWorkspace),
                const Sizer(height: 6),
                SessionStyledField(
                  controller: widget.workspaceSearchCtrl,
                  hint: S.current.searchWorkspace,
                  prefixIcon: Icons.search_rounded,
                  onChanged: cubit.setWorkspaceSearch,
                ),
                const Sizer(height: 12),
                if (cubit.filteredWorkspaces.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: Text(
                        S.current.noData,
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              color: ColorRes.anisHintText,
                            ),
                      ),
                    ),
                  )
                else
                  ...cubit.filteredWorkspaces.map(
                    (ws) => WorkspaceTile(
                      workspace: ws,
                      isSelected: state.selectedWorkspace?.id == ws.id,
                      onTap: () => cubit.selectWorkspace(ws),
                    ),
                  ),
              ],
            );
          },
        ),
      ],
    );
  }
}

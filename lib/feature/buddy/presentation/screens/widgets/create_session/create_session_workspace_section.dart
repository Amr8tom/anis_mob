import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../../workspaces/domain/entity/workspace_entity.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';

/// Section 3 — searchable workspace picker backed by [WorkspaceEntity] data
/// fetched from the workspaces repository (same dummy data workspaces feature uses).
class CreateSessionWorkspaceSection extends StatefulWidget {
  const CreateSessionWorkspaceSection({super.key});

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
        SessionSectionLabel(S.current.sessionPlace),
        const Sizer(height: 10),
        BlocBuilder<BuddyCubit, BuddyState>(
          buildWhen: (p, c) =>
              p.selectedWorkspace?.id != c.selectedWorkspace?.id ||
              p.workspaceSearchQuery != c.workspaceSearchQuery ||
              p.workspacesStatus != c.workspacesStatus,
          builder: (context, state) {
            return SessionFormCard(
              children: [
                SessionFieldLabel(S.current.selectWorkspace),
                const Sizer(height: 8),

                // ── Search field ────────────────────────────
                SessionStyledField(
                  controller: cubit.workspaceSearchCtrl,
                  hint: S.current.searchWorkspace,
                  prefixIcon: Icons.search_rounded,
                  onChanged: cubit.setWorkspaceSearch,
                ),
                const Sizer(height: 10),

                // ── Loading / empty / list ──────────────────
                if (state.workspacesStatus == BuddyStatus.loading)
                  Padding(
                    padding: EdgeInsets.symmetric(vertical: AppSizes.md),
                    child: Center(
                      child: SizedBox(
                        width: AppSizes.iconMd,
                        height: AppSizes.iconMd,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: ColorRes.anisGreen,
                        ),
                      ),
                    ),
                  )
                else if (cubit.filteredWorkspaces.isEmpty)
                  Padding(
                    padding: EdgeInsets.symmetric(vertical: AppSizes.md),
                    child: Center(
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
                    (ws) => _WorkspaceTile(
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

class _WorkspaceTile extends StatelessWidget {
  final WorkspaceEntity workspace;
  final bool isSelected;
  final VoidCallback onTap;
  const _WorkspaceTile({
    required this.workspace,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        margin: EdgeInsets.only(bottom: AppSizes.sm),
        padding: EdgeInsets.all(AppSizes.sm + 4),
        decoration: BoxDecoration(
          color: isSelected
              ? ColorRes.anisGreen.withOpacity(0.07)
              : ColorRes.anisChipBg,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: isSelected ? ColorRes.anisGreen : ColorRes.anisLine,
            width: isSelected ? 1.5 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(
              Icons.location_on_rounded,
              size: AppSizes.iconSm,
              color: isSelected ? ColorRes.anisGreen : ColorRes.anisHintText,
            ),
            const Sizer(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    workspace.name,
                    textAlign: TextAlign.start,
                    style: tt.bodyMedium?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: isSelected
                          ? ColorRes.anisGreen
                          : ColorRes.anisNavy,
                    ),
                  ),
                  Text(
                    workspace.address,
                    textAlign: TextAlign.start,
                    style: tt.bodySmall
                        ?.copyWith(color: ColorRes.anisHintText),
                  ),
                ],
              ),
            ),
            if (isSelected)
              Icon(Icons.check_circle_rounded,
                  size: AppSizes.iconSm, color: ColorRes.anisGreen),
          ],
        ),
      ),
    );
  }
}

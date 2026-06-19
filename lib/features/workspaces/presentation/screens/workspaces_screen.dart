import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';
import '../../../home/presentation/controller/home_cubit.dart';
import '../../../home/presentation/screens/qr_scanner_screen.dart';
import '../../domain/entity/workspace_entity.dart';

import '../controller/workspace_cubit.dart';
import 'widgets/workspace/workspace_divider_section.dart';
import 'widgets/workspace/workspace_empty_section.dart';
import 'widgets/workspace/workspace_error_section.dart';
import 'widgets/workspace/workspace_filter_chips_section.dart';
import 'widgets/workspace/workspace_footer_spacing_section.dart';
import 'widgets/workspace/workspace_loading_section.dart';
import 'widgets/workspace/workspace_page_title_section.dart';
import 'widgets/workspace/workspace_workspaces_list_section.dart';

class WorkspacesScreen extends StatelessWidget {
  const WorkspacesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorRes.white,
      body: SafeArea(
        child: RefreshIndicator(
          color: ColorRes.anisGreen,
          onRefresh: () => context.read<WorkspaceCubit>().refresh(),
          child: CustomScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            slivers: [
              const WorkspacePageTitleSection(),
              const WorkspaceFilterChipsSection(),
              const WorkspaceDividerSection(),
              BlocBuilder<WorkspaceCubit, WorkspaceState>(
                builder: (context, state) {
                  switch (state.status) {
                    case WorkspaceStatus2.initial:
                    case WorkspaceStatus2.loading:
                      return const WorkspaceLoadingSection();
                    case WorkspaceStatus2.failure:
                      return WorkspaceErrorSection(
                        message: state.errorMessage ?? '',
                        onRetry: () =>
                            context.read<WorkspaceCubit>().loadWorkspaces(),
                      );
                    case WorkspaceStatus2.success:
                      if (state.workspaces.isEmpty) {
                        return const WorkspaceEmptySection();
                      }
                      return WorkspaceWorkspacesListSection(
                        workspaces: state.workspaces,
                        onCheckIn: (workspace) =>
                            _onCheckIn(context, workspace),
                        onTap: (workspace) => context.pushNamed(
                          DRoutesName.workspaceDetailsRoute,
                          arguments: {'workspace': workspace},
                        ),
                      );
                  }
                },
              ),
              const WorkspaceFooterSpacingSection(),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _onCheckIn(BuildContext context, WorkspaceEntity w) async {
    final cubit = context.read<HomeCubit>();
    final checkedIn = await Navigator.of(context).push<bool>(
      MaterialPageRoute(
        builder: (_) => BlocProvider.value(
          value: cubit,
          child: const QrScannerScreen(),
        ),
      ),
    );

    if (!context.mounted || checkedIn != true) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          S.current.checkInSuccess,
          style: Theme.of(context)
              .textTheme
              .bodyMedium
              ?.copyWith(color: ColorRes.white),
        ),
        backgroundColor: ColorRes.anisGreen,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}


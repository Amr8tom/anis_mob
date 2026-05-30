import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/custom_ui.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../domain/entity/workspace_entity.dart';
import '../controller/workspace_cubit.dart';
import 'widgets/workspace_card.dart';

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
              // ── Page title ───────────────────────────────────────────────
              SliverToBoxAdapter(
                child: Padding(
                  padding: EdgeInsets.fromLTRB(
                    AppSizes.padding,
                    AppSizes.md,
                    AppSizes.padding,
                    AppSizes.sm,
                  ),
                  child: _PageTitle(),
                ),
              ),

              // ── Filter chips ─────────────────────────────────────────────
              SliverToBoxAdapter(
                child: BlocBuilder<WorkspaceCubit, WorkspaceState>(
                  buildWhen: (p, c) => p.activeFilter != c.activeFilter,
                  builder: (context, state) => _FilterChips(
                    active: state.activeFilter,
                    onSelected: (f) =>
                        context.read<WorkspaceCubit>().setFilter(f),
                  ),
                ),
              ),

              // ── Divider ─────────────────────────────────────────────────
              SliverToBoxAdapter(
                child: Container(
                  height: 1,
                  color: ColorRes.accent.withOpacity(0.5),
                ),
              ),

              // ── List ─────────────────────────────────────────────────────
              BlocBuilder<WorkspaceCubit, WorkspaceState>(
                builder: (context, state) {
                  if (state.status == WorkspaceStatus2.failure) {
                    return SliverFillRemaining(
                      child: CustomUI.anisErrorState(
                        context: context,
                        message: state.errorMessage ?? '',
                        onRetry: () =>
                            context.read<WorkspaceCubit>().loadWorkspaces(),
                      ),
                    );
                  }

                  final isLoading =
                      state.status == WorkspaceStatus2.loading ||
                          state.status == WorkspaceStatus2.initial;

                  if (isLoading) {
                    return SliverPadding(
                      padding: EdgeInsets.only(top: AppSizes.md),
                      sliver: SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (_, i) => Skeletonizer(
                            enabled: true,
                            child: WorkspaceCard(
                              workspace: _skeletonWorkspace(i),
                            ),
                          ),
                          childCount: 4,
                        ),
                      ),
                    );
                  }

                  if (state.workspaces.isEmpty) {
                    return SliverFillRemaining(
                      child: CustomUI.anisEmptyState(
                        context: context,
                        icon: Icons.store_outlined,
                        title: S.current.noWorkspacesFound,
                      ),
                    );
                  }

                  return SliverPadding(
                    padding: EdgeInsets.only(top: AppSizes.md),
                    sliver: SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (_, i) => WorkspaceCard(
                          workspace: state.workspaces[i],
                          onCheckIn: () =>
                              _onCheckIn(context, state.workspaces[i]),
                        ),
                        childCount: state.workspaces.length,
                      ),
                    ),
                  );
                },
              ),

              const SliverToBoxAdapter(child: Sizer(height: 24)),
            ],
          ),
        ),
      ),
    );
  }

  void _onCheckIn(BuildContext context, WorkspaceEntity w) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          '${S.current.scanToCheckIn} — ${w.name}',
          textDirection: TextDirection.rtl,
          style: Theme.of(context)
              .textTheme
              .bodyMedium
              ?.copyWith(color: ColorRes.white),
        ),
        backgroundColor: ColorRes.anisGreen,
        behavior: SnackBarBehavior.floating,
        duration: const Duration(seconds: 2),
      ),
    );
  }

  WorkspaceEntity _skeletonWorkspace(int i) {
    return WorkspaceEntity(
      id: 'sk_$i',
      name: '████████████████',
      address: '████████████',
      currentOccupancy: 10,
      capacity: 30,
      status: WorkspaceStatus.open,
      distanceKm: 1.0,
      openTime: '8:00 ص',
      closeTime: '10:00 م',
      amenities: const [],
    );
  }
}

// ─── Page title ───────────────────────────────────────────────────────────────

class _PageTitle extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(
          S.current.workspacesTab,
          textAlign: TextAlign.right,
          style: tt.headlineMedium?.copyWith(
            fontWeight: FontWeight.w800,
            color: ColorRes.anisNavy,
          ),
        ),
        const Sizer(height: 4),
        Text(
          S.current.workspacesSubtitle,
          textAlign: TextAlign.right,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
        ),
      ],
    );
  }
}

// ─── Filter chips ─────────────────────────────────────────────────────────────

class _FilterChips extends StatelessWidget {
  final String active;
  final ValueChanged<String> onSelected;
  const _FilterChips({required this.active, required this.onSelected});

  @override
  Widget build(BuildContext context) {
    final chips = [
      (key: 'all', label: S.current.allFilter),
      (key: 'openNow', label: S.current.openNow),
      (key: 'nearby', label: S.current.nearbyFilter),
    ];

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm,
      ),
      child: Row(
        textDirection: TextDirection.rtl,
        children: chips.map((chip) {
          final isActive = chip.key == active;
          return GestureDetector(
            onTap: () => onSelected(chip.key),
            child: Container(
              margin: EdgeInsets.only(left: AppSizes.sm),
              padding: EdgeInsets.symmetric(
                horizontal: AppSizes.md,
                vertical: 6.h,
              ),
              decoration: BoxDecoration(
                color: isActive ? ColorRes.anisGreen : ColorRes.white,
                borderRadius:
                    BorderRadius.circular(AppSizes.borderRadiusXLg),
                border: Border.all(
                  color: isActive ? ColorRes.anisGreen : ColorRes.accent,
                  width: 1,
                ),
              ),
              child: Text(
                chip.label,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      fontWeight: FontWeight.w600,
                      color: isActive
                          ? ColorRes.white
                          : ColorRes.anisChipText,
                      fontSize: 12.sp,
                    ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }
}


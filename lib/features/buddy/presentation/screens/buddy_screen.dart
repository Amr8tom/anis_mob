import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/action_guard.dart';
import '../../../../common/custom_ui.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';
import '../controller/buddy_cubit.dart';
import 'widgets/buddy_filter_chips.dart';
import 'widgets/buddy_page_title.dart';
import 'widgets/buddy_results_count_row.dart';
import 'widgets/buddy_search_fields.dart';
import 'widgets/buddy_session_card.dart';
import 'widgets/buddy_skeleton_session.dart';

class BuddyScreen extends StatelessWidget {
  const BuddyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorRes.white,
      floatingActionButton: _CreateSessionFab(),
      body: SafeArea(
        child: RefreshIndicator(
          color: ColorRes.anisGreen,
          onRefresh: () => context.read<BuddyCubit>().refresh(),
          child: CustomScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            slivers: [
              // ── Page title ──────────────────────────────────────────────
              SliverToBoxAdapter(
                child: Padding(
                  padding: EdgeInsets.fromLTRB(
                    AppSizes.padding,
                    AppSizes.md,
                    AppSizes.padding,
                    AppSizes.sm,
                  ),
                  child: const BuddyPageTitle(),
                ),
              ),

              // ── Search fields ───────────────────────────────────────────
              SliverToBoxAdapter(
                child: Padding(
                  padding: EdgeInsets.only(bottom: AppSizes.sm),
                  child: BuddySearchFields(
                    onUniversityChanged: (v) =>
                        context.read<BuddyCubit>().setUniversityFilter(v),
                    onSubjectChanged: (v) =>
                        context.read<BuddyCubit>().setSubjectFilter(v),
                  ),
                ),
              ),

              // ── Filter chips ────────────────────────────────────────────
              SliverToBoxAdapter(
                child: BlocBuilder<BuddyCubit, BuddyState>(
                  buildWhen: (p, c) => p.activeChip != c.activeChip,
                  builder: (context, state) => BuddyFilterChips(
                    activeChip: state.activeChip,
                    onChipSelected: (chip) =>
                        context.read<BuddyCubit>().setActiveChip(chip),
                  ),
                ),
              ),

              // ── Divider ─────────────────────────────────────────────────
              SliverToBoxAdapter(
                child: Container(
                  height: 1,
                  margin: EdgeInsets.only(top: AppSizes.sm),
                  color: ColorRes.accent.withValues(alpha: 0.5),
                ),
              ),

              // ── Results ─────────────────────────────────────────────────
              BlocBuilder<BuddyCubit, BuddyState>(
                builder: (context, state) {
                  if (state.status == BuddyStatus.failure) {
                    return SliverFillRemaining(
                      child: CustomUI.anisErrorState(
                        context: context,
                        message: state.errorMessage ?? '',
                        onRetry: () =>
                            context.read<BuddyCubit>().loadSessions(),
                      ),
                    );
                  }

                  final isLoading = state.status == BuddyStatus.loading ||
                      state.status == BuddyStatus.initial;

                  if (isLoading) {
                    return SliverPadding(
                      padding: EdgeInsets.only(top: AppSizes.md),
                      sliver: SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (_, i) => Skeletonizer(
                            enabled: true,
                            child: BuddySessionCard(
                              session: buddySkeletonSession(i),
                              onTap: () {},
                              onJoin: () {},
                            ),
                          ),
                          childCount: 4,
                        ),
                      ),
                    );
                  }

                  if (state.sessions.isEmpty) {
                    return SliverFillRemaining(
                      child: CustomUI.anisEmptyState(
                        context: context,
                        icon: Icons.people_outline_rounded,
                        title: S.current.noBuddiesFound,
                        subtitle: S.current.tryDifferentFilters,
                      ),
                    );
                  }

                  return SliverMainAxisGroup(
                    slivers: [
                      SliverToBoxAdapter(
                        child: BuddyResultsCountRow(
                          count: state.sessions.length,
                        ),
                      ),
                      SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (_, i) => BuddySessionCard(
                            session: state.sessions[i],
                            onTap: () => context.pushNamed(
                              DRoutesName.sessionDetailsRoute,
                              arguments: {'session': state.sessions[i]},
                            ),
                            onJoin: () => ActionGuard.run(
                              context,
                              isGuest: state.isGuest,
                              action: () => context
                                  .read<BuddyCubit>()
                                  .joinSession(state.sessions[i]),
                            ),
                          ),
                          childCount: state.sessions.length,
                        ),
                      ),
                    ],
                  );
                },
              ),

              // Extra padding for FAB
              const SliverToBoxAdapter(child: Sizer(height: 80)),
            ],
          ),
        ),
      ),
    );
  }
}

// ── FAB ────────────────────────────────────────────────────────────────────────

class _CreateSessionFab extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BuddyCubit, BuddyState>(
      buildWhen: (previous, current) => previous.isGuest != current.isGuest,
      builder: (context, state) {
        return FloatingActionButton.extended(
          onPressed: () => ActionGuard.run(
            context,
            isGuest: state.isGuest,
            action: () => context.pushNamed(DRoutesName.createSessionRoute),
          ),
          backgroundColor: ColorRes.anisGreen,
          foregroundColor: ColorRes.white,
          elevation: 4,
          icon: const Icon(Icons.add_rounded),
          label: Text(
            S.current.createSession,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
          ),
        );
      },
    );
  }
}

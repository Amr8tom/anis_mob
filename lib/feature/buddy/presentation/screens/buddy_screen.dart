import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/custom_ui.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../../workspaces/domain/entity/workspace_entity.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../controller/buddy_cubit.dart';
import 'widgets/buddy_filter_chips.dart';
import 'widgets/buddy_search_fields.dart';
import 'widgets/buddy_session_card.dart';

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
                    AppSizes.padding, AppSizes.md, AppSizes.padding, AppSizes.sm,
                  ),
                  child: _PageTitle(),
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
                  color: ColorRes.accent.withOpacity(0.5),
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
                        onRetry: () => context.read<BuddyCubit>().loadSessions(),
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
                              session: _skeletonSession(i),
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
                        child: _ResultsCountRow(count: state.sessions.length),
                      ),
                      SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (_, i) => BuddySessionCard(
                            session: state.sessions[i],
                            onTap: () => context
                                .read<BuddyCubit>()
                                .openSessionDetails(context, state.sessions[i]),
                            onJoin: () => context
                                .read<BuddyCubit>()
                                .joinSession(context, state.sessions[i]),
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

  BuddySessionEntity _skeletonSession(int i) {
    const names = ['████ ████', '███ ████', '████ █████'];
    const inits = ['ر.ع', 'م.ح', 'ن.إ'];
    const unis = ['███████████████', '████████████', '█████████████'];
    const keys = ['blue', 'red', 'purple'];
    final now = DateTime.now();
    return BuddySessionEntity(
      id: 'sk_$i',
      buddyName: names[i % 3],
      buddyInitials: inits[i % 3],
      avatarColorKey: keys[i % 3],
      university: unis[i % 3],
      availability: BuddyAvailability.online,
      topic: '████████████████',
      subject: '████████████',
      description: '████████████████████████████',
      startTime: now,
      timeLabel: '██████',
      workspace: const WorkspaceEntity(
        id: '', name: '██████████', address: '█████████████',
        currentOccupancy: 0, capacity: 0,
        status: WorkspaceStatus.open, distanceKm: 0,
        openTime: '', closeTime: '', amenities: [],
      ),
    );
  }
}

// ── FAB ────────────────────────────────────────────────────────────────────────

class _CreateSessionFab extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return FloatingActionButton.extended(
      onPressed: () => context.read<BuddyCubit>().openCreateSession(context),
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
  }
}

// ── Page title ─────────────────────────────────────────────────────────────────

class _PageTitle extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          S.current.findBuddy,
          textAlign: TextAlign.start,
          style: tt.headlineMedium?.copyWith(
            fontWeight: FontWeight.w800,
            color: ColorRes.anisNavy,
          ),
        ),
        const Sizer(height: 4),
        Text(
          S.current.buddyScreenSubtitle,
          textAlign: TextAlign.start,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
        ),
      ],
    );
  }
}

// ── Results count ──────────────────────────────────────────────────────────────

class _ResultsCountRow extends StatelessWidget {
  final int count;
  const _ResultsCountRow({required this.count});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding, AppSizes.md, AppSizes.padding, AppSizes.xs,
      ),
      child: Text(
        S.current.resultsCount(count),
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
          fontWeight: FontWeight.w600,
          color: ColorRes.anisTextMuted,
        ),
      ),
    );
  }
}

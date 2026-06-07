import 'package:anis/features/profile/presentation/screens/widgets/profile/profile_header_section.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';
import '../controller/profile_cubit.dart';
import 'widgets/profile/profile_badges_section.dart';
import 'widgets/profile/profile_menu_section.dart';
import 'widgets/profile/profile_stats_section.dart';
import 'widgets/profile/profile_subscription_card.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProfileCubit, ProfileState>(
      builder: (context, state) {
        if (state.isGuest) return const _GuestProfileView();

        final isLoading = state.status == ProfileStatus.initial ||
            state.status == ProfileStatus.loading;

        return Scaffold(
          backgroundColor: ColorRes.white,
          extendBodyBehindAppBar: true,
          body: RefreshIndicator(
            color: ColorRes.anisGreen,
            onRefresh: () => context.read<ProfileCubit>().refresh(),
            child: SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Skeletonizer(
                enabled: isLoading,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    /// Status bar spacer
                    Container(
                      color: ColorRes.anisGreen,
                      height: AppSizes.padding * 3,
                    ),

                    /// ── Green header with avatar, name, badge ──
                    const ProfileHeaderSection(),

                    /// ── Study stats row ────────────────────────
                    ProfileStatsSection(profile: state.profile),

                    const Sizer(height: 8),
                    Container(height: AppSizes.sm, color: ColorRes.anisChipBg),
                    const Sizer(height: 8),

                    /// ── Earned badges ──────────────────────────
                    if (!isLoading &&
                        state.profile != null &&
                        state.profile!.badges.isNotEmpty)
                      ProfileBadgesSection(badges: state.profile!.badges),

                    /// ── Subscription card ──────────────────────
                    ProfileSubscriptionCard(profile: state.profile),

                    if (state.profile != null &&
                        !state.profile!.profileCompleted)
                      _ProfileCompletionPrompt(
                        percentage: state.profile!.profileCompletionPercentage,
                      ),

                    const Sizer(height: 8),
                    Container(height: AppSizes.sm, color: ColorRes.anisChipBg),
                    const Sizer(height: 8),

                    /// ── Menu (Privacy, Language, Logout) ───────
                    const ProfileMenuSection(),

                    const Sizer(height: 32),
                  ],
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}

class _GuestProfileView extends StatelessWidget {
  const _GuestProfileView();

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: ColorRes.white,
      body: SafeArea(
        child: Center(
          child: Padding(
            padding: EdgeInsets.all(AppSizes.xl),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 72,
                  height: 72,
                  decoration: const BoxDecoration(
                    color: ColorRes.anisTagGreen,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.person_outline_rounded,
                    color: ColorRes.anisGreen,
                    size: 34,
                  ),
                ),
                const Sizer(height: 18),
                Text(
                  S.current.signInToSeeProfile,
                  textAlign: TextAlign.center,
                  style: tt.titleLarge?.copyWith(
                    color: ColorRes.anisNavy,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const Sizer(height: 7),
                Text(
                  S.current.signInToSeeProfileSubtitle,
                  textAlign: TextAlign.center,
                  style: tt.bodyMedium?.copyWith(
                    color: ColorRes.anisTextMuted,
                    height: 1.5,
                  ),
                ),
                const Sizer(height: 20),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () => context.pushReplacementNamed(
                      DRoutesName.loginRoute,
                    ),
                    icon: const Icon(Icons.login_rounded),
                    label: Text(S.current.signIn),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: ColorRes.anisGreen,
                      foregroundColor: ColorRes.white,
                      padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 5),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _ProfileCompletionPrompt extends StatelessWidget {
  final int percentage;

  const _ProfileCompletionPrompt({required this.percentage});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      child: Material(
        color: ColorRes.anisTagGreen,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        child: InkWell(
          onTap: () async {
            final profileCubit = context.read<ProfileCubit>();
            final updated =
                await context.pushNamed(DRoutesName.profileCompletionRoute);
            if (updated == true) await profileCubit.refresh();
          },
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          child: Padding(
            padding: EdgeInsets.all(AppSizes.md),
            child: Row(
              children: [
                Container(
                  width: 44,
                  height: 44,
                  decoration: const BoxDecoration(
                    color: ColorRes.white,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.auto_awesome_rounded,
                    color: ColorRes.anisGreen,
                  ),
                ),
                const Sizer(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        S.current.completeProfileTitle,
                        style: tt.bodyMedium?.copyWith(
                          color: ColorRes.anisNavy,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                      const Sizer(height: 5),
                      ClipRRect(
                        borderRadius:
                            BorderRadius.circular(AppSizes.borderRadiusXXLg),
                        child: LinearProgressIndicator(
                          value: percentage / 100,
                          minHeight: 4,
                          backgroundColor:
                              ColorRes.anisGreen.withValues(alpha: 0.15),
                          valueColor: const AlwaysStoppedAnimation(
                            ColorRes.anisGreen,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const Sizer(width: 10),
                const Icon(
                  Icons.chevron_right_rounded,
                  color: ColorRes.anisGreen,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

import 'package:anis/features/profile/presentation/screens/widgets/profile/profile_header_section.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
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

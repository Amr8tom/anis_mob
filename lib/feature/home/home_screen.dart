import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../common/widgets/sizeboxs/Sizer.dart';
import '../../core/constants/app_sizes.dart';
import '../../core/constants/colors.dart';
import '../../generated/l10n.dart';
import 'presentation/controller/home_cubit.dart';
import 'presentation/widgets/home_action_buttons_widget.dart';
import 'presentation/widgets/home_header_widget.dart';
import 'presentation/widgets/today_sessions_section.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<HomeCubit, HomeState>(
      builder: (context, state) {
        return RefreshIndicator(
          color: ColorRes.anisGreen,
          onRefresh: () => context.read<HomeCubit>().refresh(),
          child: CustomScrollView(
            physics: const BouncingScrollPhysics(
              parent: AlwaysScrollableScrollPhysics(),
            ),
            slivers: [
              // ── Green header ──────────────────────────────────
              SliverToBoxAdapter(
                child: state.userProfile != null
                    ? HomeHeaderWidget(profile: state.userProfile!)
                    : const _HeaderSkeleton(),
              ),

              // ── Action buttons ────────────────────────────────
              SliverToBoxAdapter(
                child: HomeActionButtonsWidget(
                  onScanQr: () => _onScanQr(context),
                  onSearchAnis: () => _onSearchAnis(context),
                ),
              ),

              // ── Today's sessions ──────────────────────────────
              SliverToBoxAdapter(
                child: TodaySessionsSection(
                  sessions: state.todaySessions,
                  isLoading: state.status == HomeStatus.loading,
                  onSeeAll: () {},
                  onSessionTap: (_) {},
                ),
              ),

              const SliverToBoxAdapter(child: Sizer(height: 32)),
            ],
          ),
        );
      },
    );
  }

  void _onScanQr(BuildContext context) {
    // TODO: navigate to workspace QR scanner screen
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          S.current.scanQrShort,
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

  void _onSearchAnis(BuildContext context) {
    // TODO: navigate to Buddy tab — handled by NavigationCubit.changeIndex(1)
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          S.current.searchForAnis,
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

/// Shown while the profile is still loading (before first data arrives)
class _HeaderSkeleton extends StatelessWidget {
  const _HeaderSkeleton();

  @override
  Widget build(BuildContext context) {
    return Container(
      height: AppSizes.containerLarge,
      decoration: BoxDecoration(
        color: ColorRes.anisGreen,
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(AppSizes.borderRadiusXXLg),
          bottomRight: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
    );
  }
}

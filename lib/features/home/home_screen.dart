import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../common/widgets/sizeboxs/sizer.dart';
import '../../core/constants/colors.dart';
import '../../generated/l10n.dart';
import 'presentation/controller/home_cubit.dart';
import 'presentation/screens/qr_scanner_screen.dart';
import 'presentation/widgets/home_action_buttons_widget.dart';
import 'presentation/widgets/home_header_widget.dart';
import 'presentation/widgets/header_skeleton.dart';
import 'presentation/widgets/today_sessions_section.dart';
import 'presentation/widgets/workspace_session_banner_widget.dart';
import '../../core/routing/route_names.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<HomeCubit, HomeState>(
      listenWhen: (previous, current) =>
          previous.attendanceError != current.attendanceError ||
          (previous.attendanceStatus.isCheckedIn &&
              current.attendanceStatus.isIdle),
      listener: (context, state) {
        final error = state.attendanceError;
        if (error != null) {
          _showSnackBar(
            context,
            _attendanceErrorMessage(error),
            ColorRes.anisErrorRed,
          );
          context.read<HomeCubit>().clearAttendanceError();
          return;
        }

        if (state.attendanceStatus.isIdle && state.activeSession == null) {
          _showSnackBar(context, S.current.checkOutSuccess, ColorRes.anisGreen);
        }
      },
      builder: (context, state) {
        return RefreshIndicator(
          color: ColorRes.anisGreen,
          onRefresh: () => context.read<HomeCubit>().refresh(),
          child: CustomScrollView(
            physics: const BouncingScrollPhysics(
              parent: AlwaysScrollableScrollPhysics(),
            ),
            slivers: [
              SliverToBoxAdapter(
                child: state.userProfile != null
                    ? HomeHeaderWidget(profile: state.userProfile!)
                    : const HeaderSkeletonWidget(),
              ),
              if (state.isCheckedIn)
                const SliverToBoxAdapter(
                  child: WorkspaceSessionBannerWidget(),
                ),
              SliverToBoxAdapter(
                child: HomeActionButtonsWidget(
                  onScanQr: () => _onScanQr(context),
                  onSubscribe: () => _onSubscribe(
                    context,
                    state.userProfile?.subscriptionType ?? 'free',
                  ),
                  onLeaveWorkspace: () => context.read<HomeCubit>().checkOut(),
                  isCheckedIn: state.isCheckedIn,
                  isLoading: state.attendanceStatus.isBusy,
                ),
              ),
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

  Future<void> _onScanQr(BuildContext context) async {
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
    _showSnackBar(context, S.current.checkInSuccess, ColorRes.anisGreen);
  }

  void _onSubscribe(BuildContext context, String currentPlan) {
    Navigator.of(context).pushNamed(
      DRoutesName.plansRoute,
      arguments: {'currentPlan': currentPlan},
    );
  }

  void _showSnackBar(BuildContext context, String message, Color color) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: ColorRes.white,
              ),
        ),
        backgroundColor: color,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  String _attendanceErrorMessage(String error) {
    if (error == 'invalidWorkspaceQr') return S.current.invalidWorkspaceQr;
    return error;
  }
}


// Removed private _HeaderSkeleton implementation; now using HeaderSkeletonWidget.


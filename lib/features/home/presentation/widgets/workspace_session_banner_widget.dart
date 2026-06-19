import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../controller/home_cubit.dart';

/// Green banner shown while the user is checked in to a workspace.
/// Displays elapsed time that increments every minute via a local [Timer].
class WorkspaceSessionBannerWidget extends StatefulWidget {
  const WorkspaceSessionBannerWidget({super.key});

  @override
  State<WorkspaceSessionBannerWidget> createState() =>
      _WorkspaceSessionBannerWidgetState();
}

class _WorkspaceSessionBannerWidgetState
    extends State<WorkspaceSessionBannerWidget> {
  Timer? _ticker;

  @override
  void initState() {
    super.initState();
    _ticker = Timer.periodic(
      const Duration(seconds: 30),
      (_) {
        if (mounted) setState(() {});
      },
    );
  }

  @override
  void dispose() {
    _ticker?.cancel();
    super.dispose();
  }

  String _formatElapsed(Duration d) {
    final h = d.inHours;
    final m = d.inMinutes.remainder(60);
    if (h > 0) return '${h}h ${m}m';
    return '${m}m';
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return BlocBuilder<HomeCubit, HomeState>(
      buildWhen: (p, c) =>
          p.activeSession != c.activeSession ||
          p.attendanceStatus != c.attendanceStatus,
      builder: (context, state) {
        final session = state.activeSession;
        if (session == null) return const SizedBox.shrink();

        final isPending = session.isCheckoutPending ||
            state.attendanceStatus.isCheckoutPending;
        final elapsed = _formatElapsed(session.elapsed);

        // Amber while a checkout request is awaiting owner approval; green while
        // the session is simply running.
        final gradientColors = isPending
            ? const [Color(0xFFF4A800), Color(0xFFE08E00)]
            : const [ColorRes.anisGreen, ColorRes.anisButtonGreen];
        final shadowColor =
            isPending ? const Color(0xFFF4A800) : ColorRes.anisGreen;

        final fundingSuffix =
            session.billingSource == 'WORKSPACE_SUBSCRIPTION' &&
                    session.workspaceSubscriptionRemainingMinutes != null
                ? ' • ${session.workspaceSubscriptionRemainingMinutes}m'
                : '';
        final subtitle = isPending
            ? S.current.checkoutPendingSubtitle
            : '${S.current.studyTimeLabel}: $elapsed$fundingSuffix';
        final title = isPending
            ? S.current.awaitingCheckoutApproval
            : S.current.currentWorkspace;

        return Container(
          margin: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.sm,
            AppSizes.padding,
            0,
          ),
          padding: EdgeInsets.all(AppSizes.md),
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: gradientColors,
              begin: AlignmentDirectional.centerStart,
              end: AlignmentDirectional.centerEnd,
            ),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            boxShadow: [
              BoxShadow(
                color: shadowColor.withValues(alpha: 0.30),
                blurRadius: 16,
                offset: const Offset(0, 6),
              ),
            ],
          ),
          child: Row(
            children: [
              Container(
                width: 10,
                height: 10,
                decoration: const BoxDecoration(
                  color: ColorRes.white,
                  shape: BoxShape.circle,
                ),
              ),
              const Sizer(width: 10),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      title,
                      style: tt.labelSmall?.copyWith(
                        color: ColorRes.white.withValues(alpha: 0.75),
                        fontSize: 11,
                      ),
                    ),
                    const Sizer(height: 2),
                    Text(
                      session.workspaceName,
                      style: tt.bodyMedium?.copyWith(
                        color: ColorRes.white,
                        fontWeight: FontWeight.w700,
                        fontSize: 14,
                      ),
                    ),
                    const Sizer(height: 2),
                    Text(
                      subtitle,
                      style: tt.labelSmall?.copyWith(
                        color: ColorRes.white.withValues(alpha: 0.80),
                        fontSize: 11,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                width: 42,
                height: 42,
                decoration: BoxDecoration(
                  color: ColorRes.white.withValues(alpha: 0.18),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  isPending
                      ? Icons.hourglass_top_rounded
                      : Icons.timer_outlined,
                  color: ColorRes.white,
                  size: 22,
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

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

        final elapsed = _formatElapsed(session.elapsed);
        return Container(
          margin: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.sm,
            AppSizes.padding,
            0,
          ),
          padding: EdgeInsets.all(AppSizes.md),
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [ColorRes.anisGreen, ColorRes.anisButtonGreen],
              begin: AlignmentDirectional.centerStart,
              end: AlignmentDirectional.centerEnd,
            ),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            boxShadow: [
              BoxShadow(
                color: ColorRes.anisGreen.withValues(alpha: 0.30),
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
                      S.current.currentWorkspace,
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
                      '${S.current.studyTimeLabel}: $elapsed',
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
                child: const Icon(
                  Icons.timer_outlined,
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

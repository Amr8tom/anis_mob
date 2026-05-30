import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/custom_ui.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../domain/entity/study_session_entity.dart';
import 'session_card_widget.dart';

class TodaySessionsSection extends StatelessWidget {
  final List<StudySessionEntity> sessions;
  final bool isLoading;
  final VoidCallback? onSeeAll;
  final void Function(StudySessionEntity)? onSessionTap;

  const TodaySessionsSection({
    super.key,
    required this.sessions,
    this.isLoading = false,
    this.onSeeAll,
    this.onSessionTap,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final count = sessions.length;

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.spaceBtwItems,
        AppSizes.padding,
        0,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // ── Section header ──────────────────────────────────
          Row(
            textDirection: TextDirection.rtl,
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              // Title + count badge
              Row(
                textDirection: TextDirection.rtl,
                children: [
                  Text(
                    S.current.todaysSessions,
                    textDirection: TextDirection.rtl,
                    style: tt.bodyLarge?.copyWith(
                      fontWeight: FontWeight.w800,
                      color: ColorRes.anisNavy,
                    ),
                  ),
                  if (!isLoading && count > 0) ...[
                    const Sizer(width: 8),
                    _CountBadge(count: count),
                  ],
                ],
              ),
              // See all button
              GestureDetector(
                onTap: onSeeAll,
                child: Text(
                  S.current.show,
                  style: tt.bodySmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisGreen,
                  ),
                ),
              ),
            ],
          ),
          const Sizer(height: 14),

          // ── Cards ───────────────────────────────────────────
          if (isLoading)
            Skeletonizer(
              enabled: true,
              child: Column(
                children: List.generate(
                  3,
                  (_) => const SessionCardWidget(
                    session: StudySessionEntity(
                      id: '',
                      title: 'الفيزياء — الفصل 4',
                      university: 'جامعة القاهرة',
                      timeLabel: '2:00 م',
                      tagLabel: 'فيز',
                      tagColorKey: 'blue',
                      status: SessionStatus.upcoming,
                      participantCount: 0,
                      maxParticipants: 10,
                    ),
                  ),
                ),
              ),
            )
          else if (sessions.isEmpty)
            CustomUI.anisEmptyState(
              context: context,
              icon: Icons.event_busy_rounded,
              title: S.current.noSessionsToday,
            )
          else
            ...sessions.map(
              (s) => SessionCardWidget(
                session: s,
                onTap: () => onSessionTap?.call(s),
              ),
            ),
        ],
      ),
    );
  }
}

// ── Count badge ───────────────────────────────────────────────────────────────

class _CountBadge extends StatelessWidget {
  final int count;
  const _CountBadge({required this.count});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm,
        vertical: 2.h,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGreen.withOpacity(0.12),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
      ),
      child: Text(
        '$count',
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.anisGreen,
              fontSize: 11,
            ),
      ),
    );
  }
}


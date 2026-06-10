import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import '../buddy_session_card.dart';

import 'status_chip.dart';
import 'info_pill.dart';
import 'overlapping_avatars.dart';

class SessionHeaderSection extends StatelessWidget {
  final BuddySessionEntity session;
  const SessionHeaderSection({super.key, required this.session});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final topPad = MediaQuery.of(context).padding.top;

    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [Color(0xFF15472A), Color(0xFF2E7D47)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // ── Status-bar spacer + action row ───────────────
          SizedBox(height: topPad + AppSizes.xs),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: AppSizes.md),
            child: Row(
              children: [
                // Back button
                GestureDetector(
                  onTap: () => Navigator.of(context).pop(),
                  child: Container(
                    width: AppSizes.iconXLarge,
                    height: AppSizes.iconXLarge,
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.15),
                      shape: BoxShape.circle,
                    ),
                    child: Icon(
                      Icons.arrow_back_ios_new_rounded,
                      color: ColorRes.white,
                      size: AppSizes.iconSm,
                    ),
                  ),
                ),
                const Spacer(),
                // Status chip
                StatusChip(session: session),
              ],
            ),
          ),
          const Sizer(height: 14),

          // ── Subject chip + bold topic ─────────────────────
          Padding(
            padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Subject pill
                Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: AppSizes.sm,
                    vertical: AppSizes.xs,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.13),
                    borderRadius:
                        BorderRadius.circular(AppSizes.borderRadiusMd),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(
                        Icons.menu_book_rounded,
                        size: AppSizes.iconXs,
                        color: Colors.white.withValues(alpha: 0.85),
                      ),
                      const Sizer(width: 5),
                      Text(
                        session.subject,
                        style: tt.bodySmall?.copyWith(
                          color: Colors.white.withValues(alpha: 0.9),
                          fontWeight: FontWeight.w600,
                          letterSpacing: 0.3,
                        ),
                      ),
                    ],
                  ),
                ),
                const Sizer(height: 10),

                // Topic title
                Text(
                  session.topic,
                  textAlign: TextAlign.start,
                  style: tt.headlineSmall?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.white,
                    height: 1.2,
                    letterSpacing: -0.3,
                  ),
                ),
              ],
            ),
          ),
          const Sizer(height: 14),

          // ── Quick-info pills ──────────────────────────────
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
            child: Row(
              children: [
                InfoPill(
                  icon: Icons.schedule_rounded,
                  label: session.timeLabel,
                ),
                const Sizer(width: 8),
                InfoPill(
                  icon: Icons.calendar_today_rounded,
                  label: _shortDate(session.startTime),
                ),
                const Sizer(width: 8),
                InfoPill(
                  icon: Icons.people_rounded,
                  label: '${session.memberCount}/${session.maxCapacity}',
                ),
              ],
            ),
          ),
          const Sizer(height: 16),

          // ── Founder row + overlapping member avatars ──────
          Padding(
            padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
            child: Row(
              children: [
                BuddyAvatarWithDot(
                  initials: session.buddyInitials,
                  colorKey: session.avatarColorKey,
                  availability: session.availability,
                ),
                const Sizer(width: 10),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        S.current.sessionFounder,
                        style: tt.bodySmall?.copyWith(
                          color: Colors.white.withValues(alpha: 0.6),
                        ),
                      ),
                      Text(
                        session.buddyName,
                        textAlign: TextAlign.start,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.white,
                        ),
                      ),
                    ],
                  ),
                ),
                if (session.members.isNotEmpty)
                  OverlappingAvatars(members: session.members),
              ],
            ),
          ),
          const Sizer(height: 20),
        ],
      ),
    );
  }

  String _shortDate(DateTime dt) {
    const months = [
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec',
    ];
    return '${dt.day} ${months[dt.month - 1]}';
  }
}

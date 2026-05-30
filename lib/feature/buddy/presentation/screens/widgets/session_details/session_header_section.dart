import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_member_entity.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import '../buddy_session_card.dart';

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
                      color: Colors.white.withOpacity(0.15),
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
                _StatusChip(session: session),
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
                    color: Colors.white.withOpacity(0.13),
                    borderRadius:
                        BorderRadius.circular(AppSizes.borderRadiusMd),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(
                        Icons.menu_book_rounded,
                        size: AppSizes.iconXs,
                        color: Colors.white.withOpacity(0.85),
                      ),
                      const Sizer(width: 5),
                      Text(
                        session.subject,
                        style: tt.bodySmall?.copyWith(
                          color: Colors.white.withOpacity(0.9),
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
            padding:
                EdgeInsets.symmetric(horizontal: AppSizes.padding),
            child: Row(
              children: [
                _InfoPill(
                  icon: Icons.schedule_rounded,
                  label: session.timeLabel,
                ),
                const Sizer(width: 8),
                _InfoPill(
                  icon: Icons.calendar_today_rounded,
                  label: _shortDate(session.startTime),
                ),
                const Sizer(width: 8),
                _InfoPill(
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
                          color: Colors.white.withOpacity(0.6),
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
                  _OverlappingAvatars(members: session.members),
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
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
    ];
    return '${dt.day} ${months[dt.month - 1]}';
  }
}

// ── Status chip ───────────────────────────────────────────────────────────────

class _StatusChip extends StatelessWidget {
  final BuddySessionEntity session;
  const _StatusChip({required this.session});

  Color _color() {
    switch (session.sessionStatus) {
      case BuddySessionStatus.open:
        return ColorRes.anisOnlineGreen;
      case BuddySessionStatus.full:
        return ColorRes.anisBusyAmber;
      case BuddySessionStatus.inProgress:
        return ColorRes.anisTagBlueTxt;
    }
  }

  String _label() {
    switch (session.sessionStatus) {
      case BuddySessionStatus.open:
        return S.current.sessionOpen;
      case BuddySessionStatus.full:
        return S.current.sessionFull;
      case BuddySessionStatus.inProgress:
        return S.current.sessionInProgress;
    }
  }

  @override
  Widget build(BuildContext context) {
    final c = _color();
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: c.withOpacity(0.18),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(color: c.withOpacity(0.5)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: AppSizes.xs + 2,
            height: AppSizes.xs + 2,
            decoration: BoxDecoration(color: c, shape: BoxShape.circle),
          ),
          const Sizer(width: 5),
          Text(
            _label(),
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: c,
                  fontWeight: FontWeight.w700,
                ),
          ),
        ],
      ),
    );
  }
}

// ── Info pill ─────────────────────────────────────────────────────────────────

class _InfoPill extends StatelessWidget {
  final IconData icon;
  final String label;
  const _InfoPill({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs + 1,
      ),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.13),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(color: Colors.white.withOpacity(0.2)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: AppSizes.iconXs, color: ColorRes.white),
          const Sizer(width: 5),
          Text(
            label,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.white,
                  fontWeight: FontWeight.w600,
                ),
          ),
        ],
      ),
    );
  }
}

// ── Overlapping member avatar stack ──────────────────────────────────────────

class _OverlappingAvatars extends StatelessWidget {
  final List<BuddyMemberEntity> members;
  const _OverlappingAvatars({required this.members});

  @override
  Widget build(BuildContext context) {
    final shown = members.take(3).toList();
    final double size = AppSizes.ld;           // 24sp
    final double overlap = AppSizes.sm + 2;    // 10sp
    final double totalW = size + (shown.length - 1) * (size - overlap);

    return SizedBox(
      width: totalW,
      height: size,
      child: Stack(
        children: shown.asMap().entries.map((e) {
          return Positioned(
            left: e.key * (size - overlap),
            child: Container(
              width: size,
              height: size,
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.22),
                shape: BoxShape.circle,
                border: Border.all(
                  color: Colors.white.withOpacity(0.6),
                  width: 1.5,
                ),
              ),
              alignment: Alignment.center,
              child: Text(
                e.value.initials,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      fontSize: 9,
                      fontWeight: FontWeight.w800,
                      color: ColorRes.white,
                    ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/entity/buddy_session_entity.dart';

class BuddySessionCard extends StatelessWidget {
  final BuddySessionEntity session;
  final VoidCallback onJoin;

  const BuddySessionCard({
    super.key,
    required this.session,
    required this.onJoin,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      margin: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.sm * 0.6,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.07),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.03),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Padding(
        padding: EdgeInsets.all(AppSizes.spaceBetweenIcon),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // ── Top row: avatar + info + join button ──────────────
            Row(
              textDirection: TextDirection.rtl,
              children: [
                // Avatar with availability dot
                BuddyAvatarWithDot(
                  initials: session.buddyInitials,
                  colorKey: session.avatarColorKey,
                  availability: session.availability,
                ),
                const Sizer(width: 12),
                // Name + university + availability label
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        session.buddyName,
                        textAlign: TextAlign.right,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.anisTextDark,
                        ),
                      ),
                      const Sizer(height: 2),
                      Row(
                        textDirection: TextDirection.rtl,
                        children: [
                          _AvailabilityDot(availability: session.availability),
                          const Sizer(width: 4),
                          Text(
                            _availabilityLabel(session.availability),
                            style: tt.bodySmall?.copyWith(
                              color: _availabilityColor(session.availability),
                              fontWeight: FontWeight.w600,
                              fontSize: 11,
                            ),
                          ),
                          const Sizer(width: 6),
                          Container(
                            width: 3.r,
                            height: 3.r,
                            decoration: const BoxDecoration(
                              color: ColorRes.anisHintText,
                              shape: BoxShape.circle,
                            ),
                          ),
                          const Sizer(width: 6),
                          Flexible(
                            child: Text(
                              session.university,
                              textAlign: TextAlign.right,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: tt.bodySmall?.copyWith(
                                color: ColorRes.anisTextMuted,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const Sizer(width: 10),
                // Join button — outlined style
                _JoinButton(onTap: onJoin, tt: tt),
              ],
            ),

            const Sizer(height: 14),

            // ── Divider ────────────────────────────────────────────
            Container(
              height: 1,
              color: ColorRes.accent.withOpacity(0.5),
            ),

            const Sizer(height: 12),

            // ── Bottom row: subject + time ─────────────────────────
            Row(
              textDirection: TextDirection.rtl,
              children: [
                _IconLabel(
                  icon: Icons.menu_book_outlined,
                  label: session.subject,
                ),
                const Spacer(),
                _IconLabel(
                  icon: Icons.schedule_rounded,
                  label: session.timeLabel,
                  color: session.isOnline
                      ? ColorRes.anisGreen
                      : ColorRes.anisTextMuted,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _availabilityLabel(BuddyAvailability av) {
    switch (av) {
      case BuddyAvailability.online:
        return S.current.onlineNow;
      case BuddyAvailability.busy:
        return S.current.workspaceBusy;
      case BuddyAvailability.offline:
        return S.current.lastSeen;
    }
  }

  Color _availabilityColor(BuddyAvailability av) {
    switch (av) {
      case BuddyAvailability.online:
        return ColorRes.anisOnlineGreen;
      case BuddyAvailability.busy:
        return ColorRes.anisBusyAmber;
      case BuddyAvailability.offline:
        return ColorRes.anisHintText;
    }
  }
}

// ── Join button — outlined ────────────────────────────────────────────────────

class _JoinButton extends StatelessWidget {
  final VoidCallback onTap;
  final TextTheme tt;
  const _JoinButton({required this.onTap, required this.tt});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.xs + 2,
        ),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: Border.all(color: ColorRes.anisGreen, width: 1.5),
        ),
        child: Text(
          S.current.joinSession,
          style: tt.bodySmall?.copyWith(
            fontWeight: FontWeight.w700,
            color: ColorRes.anisGreen,
          ),
        ),
      ),
    );
  }
}

// ── Avatar with availability dot ──────────────────────────────────────────────

class BuddyAvatarWithDot extends StatelessWidget {
  final String initials;
  final String colorKey;
  final BuddyAvailability availability;

  const BuddyAvatarWithDot({
    super.key,
    required this.initials,
    required this.colorKey,
    required this.availability,
  });

  Color _avatarBg() {
    switch (colorKey) {
      case 'red':
        return ColorRes.anisAvatarD;
      case 'blue':
        return ColorRes.anisAvatarA;
      case 'purple':
        return ColorRes.anisAvatarC;
      case 'green':
        return ColorRes.anisAvatarA;
      case 'orange':
        return ColorRes.anisAvatarB;
      default:
        return ColorRes.anisAvatarB;
    }
  }

  Color _dotColor() {
    switch (availability) {
      case BuddyAvailability.online:
        return ColorRes.anisOnlineGreen;
      case BuddyAvailability.busy:
        return ColorRes.anisBusyAmber;
      case BuddyAvailability.offline:
        return ColorRes.anisHintText;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        Container(
          width: 46.r,
          height: 46.r,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: _avatarBg(),
          ),
          alignment: Alignment.center,
          child: Text(
            initials,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.anisGreen,
                ),
          ),
        ),
        Positioned(
          bottom: 0,
          left: 0,
          child: Container(
            width: 13.r,
            height: 13.r,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: _dotColor(),
              border: Border.all(color: ColorRes.white, width: 2),
            ),
          ),
        ),
      ],
    );
  }
}

// ── Small inline dot ─────────────────────────────────────────────────────────

class _AvailabilityDot extends StatelessWidget {
  final BuddyAvailability availability;
  const _AvailabilityDot({required this.availability});

  Color _color() {
    switch (availability) {
      case BuddyAvailability.online:
        return ColorRes.anisOnlineGreen;
      case BuddyAvailability.busy:
        return ColorRes.anisBusyAmber;
      case BuddyAvailability.offline:
        return ColorRes.anisHintText;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 7.r,
      height: 7.r,
      decoration: BoxDecoration(shape: BoxShape.circle, color: _color()),
    );
  }
}

// ── Icon + label ──────────────────────────────────────────────────────────────

class _IconLabel extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color color;

  const _IconLabel({
    required this.icon,
    required this.label,
    this.color = ColorRes.anisTextMuted,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      textDirection: TextDirection.rtl,
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: AppSizes.iconXs, color: color),
        const Sizer(width: 4),
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(color: color),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
  }
}

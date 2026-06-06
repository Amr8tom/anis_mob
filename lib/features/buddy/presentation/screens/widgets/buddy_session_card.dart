import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/entity/buddy_session_entity.dart';

class BuddySessionCard extends StatelessWidget {
  final BuddySessionEntity session;
  final VoidCallback onTap;
  final VoidCallback onJoin;

  const BuddySessionCard({
    super.key,
    required this.session,
    required this.onTap,
    required this.onJoin,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final isFull = session.sessionStatus == BuddySessionStatus.full;

    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.symmetric(
          horizontal: AppSizes.padding,
          vertical: AppSizes.xs + 2,
        ),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.07),
              blurRadius: AppSizes.md,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Padding(
          padding: EdgeInsets.all(AppSizes.spaceBetweenIcon),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // ── Top row: avatar + info + join ───────────────────
              Row(
                children: [
                  BuddyAvatarWithDot(
                    initials: session.buddyInitials,
                    colorKey: session.avatarColorKey,
                    availability: session.availability,
                  ),
                  const Sizer(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          session.buddyName,
                          textAlign: TextAlign.start,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: tt.bodyMedium?.copyWith(
                            fontWeight: FontWeight.w700,
                            color: ColorRes.anisTextDark,
                          ),
                        ),
                        const Sizer(height: 2),
                        Row(
                          children: [
                            _AvailabilityDot(
                                availability: session.availability),
                            const Sizer(width: 4),
                            Text(
                              _availabilityLabel(session.availability),
                              style: tt.bodySmall?.copyWith(
                                color: _availabilityColor(session.availability),
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                            const Sizer(width: 6),
                            Container(
                              width: AppSizes.xs - 1,
                              height: AppSizes.xs - 1,
                              decoration: const BoxDecoration(
                                color: ColorRes.anisHintText,
                                shape: BoxShape.circle,
                              ),
                            ),
                            const Sizer(width: 6),
                            Flexible(
                              child: Text(
                                session.university,
                                textAlign: TextAlign.start,
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
                  const Sizer(width: 8),
                  _JoinButton(onTap: onJoin, isFull: isFull, tt: tt),
                ],
              ),

              const Sizer(height: 12),

              // Topic
              Text(
                session.topic,
                textAlign: TextAlign.start,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: tt.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w600,
                  color: ColorRes.anisNavy,
                ),
              ),

              const Sizer(height: 12),

              // Divider
              Container(
                  height: 1, color: ColorRes.accent.withValues(alpha: 0.5)),
              const Sizer(height: 10),

              // ── Bottom row ───────────────────────────────────────
              Row(
                children: [
                  _IconLabel(
                    icon: Icons.menu_book_outlined,
                    label: session.subject,
                  ),
                  const Spacer(),
                  // capacity
                  _IconLabel(
                    icon: Icons.people_outline_rounded,
                    label: '${session.memberCount}/${session.maxCapacity}',
                    color: isFull ? ColorRes.anisBusyAmber : ColorRes.anisGreen,
                  ),
                  const Sizer(width: 10),
                  _IconLabel(
                    icon: Icons.schedule_rounded,
                    label: session.timeLabel,
                    color: session.isOnline
                        ? ColorRes.anisGreen
                        : ColorRes.anisTextMuted,
                  ),
                ],
              ),

              // Gift hint
              if (session.gift != null) ...[
                const Sizer(height: 8),
                Row(
                  children: [
                    Icon(Icons.card_giftcard_rounded,
                        size: AppSizes.iconXs, color: ColorRes.anisGreen),
                    const Sizer(width: 5),
                    Flexible(
                      child: Text(
                        session.gift!,
                        textAlign: TextAlign.start,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: tt.bodySmall?.copyWith(
                          color: ColorRes.anisGreen,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ],
          ),
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

// ── Join button ───────────────────────────────────────────────────────────────

class _JoinButton extends StatelessWidget {
  final VoidCallback onTap;
  final bool isFull;
  final TextTheme tt;
  const _JoinButton(
      {required this.onTap, required this.isFull, required this.tt});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: isFull ? null : onTap,
      child: Container(
        padding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.xs + 2,
        ),
        decoration: BoxDecoration(
          color: isFull ? ColorRes.anisChipBg : ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: Border.all(
            color: isFull ? ColorRes.anisHintText : ColorRes.anisGreen,
            width: 1.5,
          ),
        ),
        child: Text(
          isFull ? S.current.sessionFull : S.current.joinSession,
          style: tt.bodySmall?.copyWith(
            fontWeight: FontWeight.w700,
            color: isFull ? ColorRes.anisHintText : ColorRes.anisGreen,
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
    final avatarSize = AppSizes.iconXLarge + AppSizes.sm;
    final dotSize = AppSizes.iconXs + 1;

    return Stack(
      clipBehavior: Clip.none,
      children: [
        Container(
          width: avatarSize,
          height: avatarSize,
          decoration: BoxDecoration(shape: BoxShape.circle, color: _avatarBg()),
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
            width: dotSize,
            height: dotSize,
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

// ── Small inline dot ──────────────────────────────────────────────────────────

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
      width: AppSizes.xs + 3,
      height: AppSizes.xs + 3,
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

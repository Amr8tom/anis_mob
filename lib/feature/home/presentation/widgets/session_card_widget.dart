import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../domain/entity/study_session_entity.dart';

/// Maps a [tagColorKey] string to a background [Color].
Color tagBgColor(String key) {
  switch (key) {
    case 'yellow':
      return ColorRes.anisTagYellow;
    case 'pink':
      return ColorRes.anisTagPink;
    case 'green':
      return ColorRes.anisTagGreen;
    default: // 'blue'
      return ColorRes.anisTagBlue;
  }
}

/// Maps a [tagColorKey] string to a text/foreground [Color].
Color tagFgColor(String key) {
  switch (key) {
    case 'yellow':
      return ColorRes.anisTagYellowTxt;
    case 'pink':
      return ColorRes.anisTagPinkTxt;
    case 'green':
      return ColorRes.anisTagGreenTxt;
    default:
      return ColorRes.anisTagBlueTxt;
  }
}

class SessionCardWidget extends StatelessWidget {
  final StudySessionEntity session;
  final VoidCallback? onTap;

  const SessionCardWidget({
    super.key,
    required this.session,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final isLive = session.status == SessionStatus.inProgress;

    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: EdgeInsets.only(bottom: AppSizes.spaceBetweenIcon),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withOpacity(0.06),
              blurRadius: 14,
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
          padding: EdgeInsets.symmetric(
            horizontal: AppSizes.spaceBetweenIcon,
            vertical: AppSizes.sm + 2,
          ),
          child: Row(
            textDirection: TextDirection.rtl,
            children: [
              // ── Tag avatar ────────────────────────────────────
              Container(
                width: 46.r,
                height: 46.r,
                decoration: BoxDecoration(
                  color: tagBgColor(session.tagColorKey),
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd + 2),
                ),
                alignment: Alignment.center,
                child: Text(
                  session.tagLabel,
                  style: tt.bodyMedium?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: tagFgColor(session.tagColorKey),
                  ),
                ),
              ),
              const Sizer(width: 12),

              // ── Title + sub-info ──────────────────────────────
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      session.title,
                      textDirection: TextDirection.rtl,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: tt.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: ColorRes.anisNavy,
                      ),
                    ),
                    const Sizer(height: 4),
                    Row(
                      textDirection: TextDirection.rtl,
                      children: [
                        // Time
                        _MetaChip(
                          icon: Icons.schedule_rounded,
                          label: session.timeLabel,
                        ),
                        const Sizer(width: 6),
                        _Dot(),
                        const Sizer(width: 6),
                        // University (truncated)
                        Flexible(
                          child: Text(
                            session.university,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: tt.bodySmall?.copyWith(
                              color: ColorRes.anisChipText,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              const Sizer(width: 8),

              // ── Status badge or chevron ────────────────────────
              if (isLive)
                _LiveBadge(tt: tt)
              else
                Icon(
                  Icons.chevron_left_rounded,
                  color: ColorRes.anisHintText,
                  size: AppSizes.iconSm,
                ),
            ],
          ),
        ),
      ),
    );
  }
}

// ── Live badge ────────────────────────────────────────────────────────────────

class _LiveBadge extends StatelessWidget {
  final TextTheme tt;
  const _LiveBadge({required this.tt});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm,
        vertical: AppSizes.xs + 1,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGreen,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 6.r,
            height: 6.r,
            decoration: const BoxDecoration(
              color: ColorRes.white,
              shape: BoxShape.circle,
            ),
          ),
          const Sizer(width: 4),
          Text(
            S.current.sessionInProgress,
            style: tt.bodySmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.white,
              fontSize: 10,
            ),
          ),
        ],
      ),
    );
  }
}

// ── Meta chip (icon + label) ──────────────────────────────────────────────────

class _MetaChip extends StatelessWidget {
  final IconData icon;
  final String label;
  const _MetaChip({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 12.r, color: ColorRes.anisHintText),
        const Sizer(width: 2),
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: ColorRes.anisChipText,
              ),
        ),
      ],
    );
  }
}

// ── Separator dot ─────────────────────────────────────────────────────────────

class _Dot extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: 3.r,
      height: 3.r,
      decoration: const BoxDecoration(
        color: ColorRes.anisHintText,
        shape: BoxShape.circle,
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/entity/buddy_member_entity.dart';

class MemberProfileSheet extends StatelessWidget {
  final BuddyMemberEntity member;
  const MemberProfileSheet({super.key, required this.member});

  static void show(BuildContext context, BuddyMemberEntity member) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => MemberProfileSheet(member: member),
    );
  }

  Color _avatarBg() {
    switch (member.avatarColorKey) {
      case 'red':
        return ColorRes.anisAvatarD;
      case 'blue':
        return ColorRes.anisAvatarA;
      case 'purple':
        return ColorRes.anisAvatarC;
      case 'orange':
        return ColorRes.anisAvatarB;
      default:
        return ColorRes.anisAvatarA;
    }
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final avatarSize = AppSizes.iconXLarge * 1.6;

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(AppSizes.borderRadiusXXLg),
          topRight: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.xl,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Drag handle
          Container(
            width: AppSizes.xl + AppSizes.md,
            height: AppSizes.xs,
            decoration: BoxDecoration(
              color: ColorRes.accent,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
            ),
          ),
          const Sizer(height: 20),

          // ── Avatar ───────────────────────────────────────
          Stack(
            children: [
              Container(
                width: avatarSize,
                height: avatarSize,
                decoration: BoxDecoration(
                  color: _avatarBg(),
                  shape: BoxShape.circle,
                  border: member.isFounder
                      ? Border.all(color: ColorRes.anisGold, width: 2.5)
                      : null,
                ),
                alignment: Alignment.center,
                child: Text(
                  member.initials,
                  style: tt.headlineSmall?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.anisGreen,
                  ),
                ),
              ),
              if (member.isFounder)
                Positioned(
                  bottom: AppSizes.xs,
                  right: AppSizes.xs,
                  child: Container(
                    padding: EdgeInsets.all(AppSizes.xs),
                    decoration: BoxDecoration(
                      color: ColorRes.anisGold,
                      shape: BoxShape.circle,
                      border: Border.all(color: ColorRes.white, width: 1.5),
                    ),
                    child: Icon(
                      Icons.star_rounded,
                      size: AppSizes.iconXs,
                      color: ColorRes.white,
                    ),
                  ),
                ),
            ],
          ),
          const Sizer(height: 14),

          // ── Name + founder badge ──────────────────────────
          if (member.isFounder)
            Container(
              padding: EdgeInsets.symmetric(
                horizontal: AppSizes.sm,
                vertical: AppSizes.xs,
              ),
              decoration: BoxDecoration(
                color: ColorRes.anisGold.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              ),
              child: Text(
                S.current.sessionFounder,
                style: tt.bodySmall?.copyWith(
                  color: ColorRes.anisGold,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          if (member.isFounder) const Sizer(height: 6),
          Text(
            member.name,
            style: tt.headlineSmall?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.anisNavy,
            ),
          ),
          const Sizer(height: 4),
          Text(
            member.university,
            style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
          ),
          const Sizer(height: 4),
          Text(
            member.studyField,
            style: tt.bodyMedium?.copyWith(
              color: ColorRes.anisGreen,
              fontWeight: FontWeight.w600,
            ),
          ),
          const Sizer(height: 16),

          // ── Stats row ─────────────────────────────────────
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _StatBubble(
                icon: Icons.star_rounded,
                color: ColorRes.anisGold,
                value: member.rating.toStringAsFixed(1),
                label: S.current.memberRating,
              ),
              const Sizer(width: 16),
              _StatBubble(
                icon: Icons.calendar_today_rounded,
                color: ColorRes.anisTagBlueTxt,
                value: '${member.totalSessions}',
                label: S.current.sessionsCount,
              ),
            ],
          ),
          const Sizer(height: 16),

          // Divider
          Container(height: 1, color: ColorRes.anisLine),
          const Sizer(height: 14),

          // ── Interests ─────────────────────────────────────
          Align(
            alignment: AlignmentDirectional.centerStart,
            child: Text(
              S.current.memberInterests,
              textAlign: TextAlign.start,
              style: tt.bodyMedium?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.anisNavy,
              ),
            ),
          ),
          const Sizer(height: 8),
          Align(
            alignment: AlignmentDirectional.centerStart,
            child: Wrap(
              spacing: AppSizes.sm,
              runSpacing: AppSizes.sm,
              alignment: WrapAlignment.start,
              children: member.interests
                  .map((interest) => Container(
                        padding: EdgeInsets.symmetric(
                          horizontal: AppSizes.sm + 2,
                          vertical: AppSizes.xs + 1,
                        ),
                        decoration: BoxDecoration(
                          color: ColorRes.anisGreen.withValues(alpha: 0.08),
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusXLg),
                          border: Border.all(
                            color: ColorRes.anisGreen.withValues(alpha: 0.2),
                            width: 1,
                          ),
                        ),
                        child: Text(
                          interest,
                          style: tt.bodySmall?.copyWith(
                            color: ColorRes.anisGreen,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ))
                  .toList(),
            ),
          ),
          const Sizer(height: 8),
        ],
      ),
    );
  }
}

class _StatBubble extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String value;
  final String label;
  const _StatBubble({
    required this.icon,
    required this.color,
    required this.value,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.md + 4,
        vertical: AppSizes.sm,
      ),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(color: color.withValues(alpha: 0.2), width: 1),
      ),
      child: Column(
        children: [
          Icon(icon, size: AppSizes.iconSm, color: color),
          const Sizer(height: 4),
          Text(
            value,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
          ),
          Text(
            label,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisHintText,
                ),
          ),
        ],
      ),
    );
  }
}

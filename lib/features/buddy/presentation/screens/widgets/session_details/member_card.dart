import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/buddy_member_entity.dart';

class MemberCard extends StatelessWidget {
  final BuddyMemberEntity member;
  final VoidCallback onTap;

  const MemberCard({
    super.key,
    required this.member,
    required this.onTap,
  });

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
    final double avatarSize = AppSizes.iconXLarge + AppSizes.md;

    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 110,
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: member.isFounder
              ? Border.all(
                  color: ColorRes.anisGold.withValues(alpha: 0.6),
                  width: 1.5,
                )
              : null,
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.05),
              blurRadius: AppSizes.sm,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        padding: EdgeInsets.symmetric(
          vertical: AppSizes.md,
          horizontal: AppSizes.sm,
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // ── Avatar ─────────────────────────────
            Stack(
              children: [
                Container(
                  width: avatarSize,
                  height: avatarSize,
                  decoration: BoxDecoration(
                    color: _avatarBg(),
                    shape: BoxShape.circle,
                    border: member.isFounder
                        ? Border.all(color: ColorRes.anisGold, width: 2)
                        : null,
                  ),
                  alignment: Alignment.center,
                  child: Text(
                    member.initials,
                    style: tt.bodyMedium?.copyWith(
                      fontWeight: FontWeight.w800,
                      color: ColorRes.anisGreen,
                    ),
                  ),
                ),
                if (member.isFounder)
                  Positioned(
                    bottom: 0,
                    right: 0,
                    child: Container(
                      width: AppSizes.iconSm,
                      height: AppSizes.iconSm,
                      decoration: BoxDecoration(
                        color: ColorRes.anisGold,
                        shape: BoxShape.circle,
                        border: Border.all(color: ColorRes.white, width: 1.5),
                      ),
                      child: Icon(
                        Icons.star_rounded,
                        size: AppSizes.xs + 2,
                        color: ColorRes.white,
                      ),
                    ),
                  ),
              ],
            ),
            const Sizer(height: 8),

            // ── Name ───────────────────────────────
            Text(
              member.name.split(' ').first,
              textAlign: TextAlign.center,
              overflow: TextOverflow.ellipsis,
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.anisNavy,
              ),
            ),
            const Sizer(height: 4),

            // ── Rating ─────────────────────────────
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(
                  Icons.star_rounded,
                  size: AppSizes.iconXs,
                  color: ColorRes.anisGold,
                ),
                const Sizer(width: 3),
                Text(
                  member.rating.toStringAsFixed(1),
                  style: tt.bodySmall?.copyWith(
                    color: ColorRes.anisHintText,
                    fontWeight: FontWeight.w600,
                    fontSize: 11,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

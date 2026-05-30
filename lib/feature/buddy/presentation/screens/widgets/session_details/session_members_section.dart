import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_member_entity.dart';
import '../member_profile_sheet.dart';

class SessionMembersSection extends StatelessWidget {
  final List<BuddyMemberEntity> members;
  final int maxCapacity;
  const SessionMembersSection({
    super.key,
    required this.members,
    required this.maxCapacity,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final emptyCount = (maxCapacity - members.length).clamp(0, 3);
    final totalItems = members.length + emptyCount;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // ── Title + count badge ──────────────────────────
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Row(
            children: [
              Container(
                width: AppSizes.iconSm + 8,
                height: AppSizes.iconSm + 8,
                decoration: BoxDecoration(
                  color: ColorRes.anisGreen.withOpacity(0.1),
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
                child: Icon(
                  Icons.people_rounded,
                  size: AppSizes.iconXs + 2,
                  color: ColorRes.anisGreen,
                ),
              ),
              const Sizer(width: 8),
              Text(
                S.current.sessionMembers,
                textAlign: TextAlign.start,
                style: tt.titleSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
              ),
              const Spacer(),
              Container(
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.sm,
                  vertical: AppSizes.xs,
                ),
                decoration: BoxDecoration(
                  color: ColorRes.anisTagGreen,
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                child: Text(
                  '${members.length}/$maxCapacity',
                  style: tt.bodySmall?.copyWith(
                    color: ColorRes.anisGreen,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ],
          ),
        ),
        const Sizer(height: 12),

        // ── Horizontal member cards ───────────────────────
        SizedBox(
          height: 150,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            physics: const BouncingScrollPhysics(),
            padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
            itemCount: totalItems,
            separatorBuilder: (_, __) => const Sizer(width: 10),
            itemBuilder: (ctx, i) {
              if (i < members.length) {
                return _MemberCard(
                  member: members[i],
                  onTap: () => MemberProfileSheet.show(ctx, members[i]),
                );
              }
              return const _EmptySlotCard();
            },
          ),
        ),
      ],
    );
  }
}

// ── Member card ───────────────────────────────────────────────────────────────

class _MemberCard extends StatelessWidget {
  final BuddyMemberEntity member;
  final VoidCallback onTap;
  const _MemberCard({required this.member, required this.onTap});

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
          borderRadius:
              BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: member.isFounder
              ? Border.all(
                  color: ColorRes.anisGold.withOpacity(0.6),
                  width: 1.5,
                )
              : null,
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withOpacity(0.05),
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
                        ? Border.all(
                            color: ColorRes.anisGold, width: 2)
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
                        border: Border.all(
                            color: ColorRes.white, width: 1.5),
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

// ── Empty slot card ───────────────────────────────────────────────────────────

class _EmptySlotCard extends StatelessWidget {
  const _EmptySlotCard();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 110,
      decoration: BoxDecoration(
        color: ColorRes.anisChipBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisLine,
          width: 1.5,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            width: AppSizes.iconXLarge,
            height: AppSizes.iconXLarge,
            decoration: BoxDecoration(
              color: ColorRes.anisLine,
              shape: BoxShape.circle,
            ),
            child: Icon(
              Icons.add_rounded,
              size: AppSizes.iconMd,
              color: ColorRes.anisHintText,
            ),
          ),
          const Sizer(height: 8),
          Text(
            S.current.openSpot,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisHintText,
                  fontSize: 11,
                ),
          ),
        ],
      ),
    );
  }
}

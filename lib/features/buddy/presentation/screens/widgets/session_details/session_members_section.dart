import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_member_entity.dart';
import '../member_profile_sheet.dart';

import 'member_card.dart';
import 'empty_slot_card.dart';

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
                  color: ColorRes.anisGreen.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
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
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
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
                return MemberCard(
                  member: members[i],
                  onTap: () => MemberProfileSheet.show(ctx, members[i]),
                );
              }
              return const EmptySlotCard();
            },
          ),
        ),
      ],
    );
  }
}

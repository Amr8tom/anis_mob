import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/buddy_member_entity.dart';

class OverlappingAvatars extends StatelessWidget {
  final List<BuddyMemberEntity> members;
  const OverlappingAvatars({super.key, required this.members});

  @override
  Widget build(BuildContext context) {
    final shown = members.take(3).toList();
    final double size = AppSizes.ld; // 24sp
    final double overlap = AppSizes.sm + 2; // 10sp
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
                color: Colors.white.withValues(alpha: 0.22),
                shape: BoxShape.circle,
                border: Border.all(
                  color: Colors.white.withValues(alpha: 0.6),
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

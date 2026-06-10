import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';

class StatusChip extends StatelessWidget {
  final BuddySessionEntity session;
  const StatusChip({super.key, required this.session});

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
        color: c.withValues(alpha: 0.18),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(color: c.withValues(alpha: 0.5)),
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

import 'package:anis/features/buddy/presentation/screens/widgets/session_details/session_gift_section.dart';
import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import 'session_members_section.dart';
import 'session_workspace_card.dart';

/// Tab 2 — People & Place: members, workspace, rules.
class SessionPeopleTab extends StatelessWidget {
  final BuddySessionEntity session;
  const SessionPeopleTab({super.key, required this.session});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Sizer(height: 16),

        // ── Members ──────────────────────────────────────
        SessionMembersSection(
          members: session.members,
          maxCapacity: session.maxCapacity,
        ),

        const Sizer(height: 24),

        // ── Workspace / Place ─────────────────────────────
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: _PeopleTabTitle(
            icon: Icons.location_on_rounded,
            label: S.current.sessionPlace,
          ),
        ),
        const Sizer(height: 10),
        SessionWorkspaceCard(session: session),
        // ── Gift (optional) ───────────────────────────────
        if (session.gift != null && session.gift!.isNotEmpty) ...[
          const Sizer(height: 20),
          SessionGiftSection(gift: session.gift),
          const Sizer(height: 70),
        ],
      ],
    );
  }
}

class _PeopleTabTitle extends StatelessWidget {
  final IconData icon;
  final String label;
  const _PeopleTabTitle({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 8,
          height: AppSizes.iconSm + 8,
          decoration: BoxDecoration(
            color: ColorRes.anisGreen.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          ),
          child: Icon(
            icon,
            size: AppSizes.iconXs + 2,
            color: ColorRes.anisGreen,
          ),
        ),
        const Sizer(width: 8),
        Text(
          label,
          textAlign: TextAlign.start,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisNavy,
              ),
        ),
      ],
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import 'session_rules_section.dart';
import 'schedule_card.dart';
import 'section_title.dart';

/// Tab 1 — Overview: schedule, description, gift.
class SessionOverviewTab extends StatelessWidget {
  final BuddySessionEntity session;
  const SessionOverviewTab({super.key, required this.session});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Sizer(height: 16),

        // ── Schedule card ─────────────────────────────────
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: ScheduleCard(session: session),
        ),
        const Sizer(height: 20),

        // ── Description ───────────────────────────────────
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SectionTitle(
                icon: Icons.article_outlined,
                label: S.current.sessionDescription,
              ),
              const Sizer(height: 10),
              Container(
                width: double.infinity,
                padding: EdgeInsets.all(AppSizes.md + 2),
                decoration: BoxDecoration(
                  color: ColorRes.white,
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                  border: Border(
                    left: BorderSide(color: ColorRes.anisGreen, width: 3),
                  ),
                  boxShadow: [
                    BoxShadow(
                      color: ColorRes.anisNavy.withValues(alpha: 0.04),
                      blurRadius: AppSizes.sm,
                      offset: const Offset(0, 2),
                    ),
                  ],
                ),
                child: Text(
                  session.description,
                  textAlign: TextAlign.start,
                  style: tt.bodyMedium?.copyWith(
                    color: ColorRes.anisTextDark,
                    height: 1.75,
                  ),
                ),
              ),
            ],
          ),
        ),

        // ── Attendance rules (optional) ───────────────────
        if (session.rules.isNotEmpty) ...[
          const Sizer(height: 20),
          SessionRulesSection(rules: session.rules),
        ],

        const Sizer(height: 70),
      ],
    );
  }
}

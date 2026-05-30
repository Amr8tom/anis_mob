import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import 'session_gift_section.dart';
import 'session_rules_section.dart';

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
          child: _ScheduleCard(session: session),
        ),
        const Sizer(height: 20),

        // ── Description ───────────────────────────────────
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _SectionTitle(
                icon: Icons.article_outlined,
                label: S.current.sessionDescription,
              ),
              const Sizer(height: 10),
              Container(
                width: double.infinity,
                padding: EdgeInsets.all(AppSizes.md + 2),
                decoration: BoxDecoration(
                  color: ColorRes.white,
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusLg),
                  border: Border(
                    left: BorderSide(
                        color: ColorRes.anisGreen, width: 3),
                  ),
                  boxShadow: [
                    BoxShadow(
                      color: ColorRes.anisNavy.withOpacity(0.04),
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

// ── Schedule card — time + date side by side ──────────────────────────────────

class _ScheduleCard extends StatelessWidget {
  final BuddySessionEntity session;
  const _ScheduleCard({required this.session});

  String _formatDate(DateTime dt) {
    const months = [
      'January', 'February', 'March', 'April',
      'May', 'June', 'July', 'August',
      'September', 'October', 'November', 'December',
    ];
    return '${dt.day} ${months[dt.month - 1]}, ${dt.year}';
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.05),
            blurRadius: AppSizes.sm + 2,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: IntrinsicHeight(
        child: Row(
          children: [
            Expanded(
              child: _ScheduleTile(
                icon: Icons.schedule_rounded,
                iconBg: ColorRes.anisGreen.withOpacity(0.1),
                iconColor: ColorRes.anisGreen,
                label: S.current.sessionTime,
                value: session.timeLabel,
              ),
            ),
            VerticalDivider(
              width: 1,
              thickness: 1,
              color: ColorRes.anisLine,
              indent: AppSizes.md,
              endIndent: AppSizes.md,
            ),
            Expanded(
              child: _ScheduleTile(
                icon: Icons.calendar_today_rounded,
                iconBg: ColorRes.anisTagBlueTxt.withOpacity(0.1),
                iconColor: ColorRes.anisTagBlueTxt,
                label: S.current.sessionDate,
                value: _formatDate(session.startTime),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _ScheduleTile extends StatelessWidget {
  final IconData icon;
  final Color iconBg;
  final Color iconColor;
  final String label;
  final String value;
  const _ScheduleTile({
    required this.icon,
    required this.iconBg,
    required this.iconColor,
    required this.label,
    required this.value,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.all(AppSizes.md),
      child: Row(
        children: [
          Container(
            width: AppSizes.iconXLarge,
            height: AppSizes.iconXLarge,
            decoration: BoxDecoration(
              color: iconBg,
              borderRadius:
                  BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            child: Icon(icon, size: AppSizes.iconMd, color: iconColor),
          ),
          const Sizer(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: tt.bodySmall
                      ?.copyWith(color: ColorRes.anisHintText),
                ),
                const Sizer(height: 2),
                Text(
                  value,
                  textAlign: TextAlign.start,
                  style: tt.bodySmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisNavy,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

// ── Section title with icon badge ─────────────────────────────────────────────

class _SectionTitle extends StatelessWidget {
  final IconData icon;
  final String label;
  const _SectionTitle({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 8,
          height: AppSizes.iconSm + 8,
          decoration: BoxDecoration(
            color: ColorRes.anisGreen.withOpacity(0.1),
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

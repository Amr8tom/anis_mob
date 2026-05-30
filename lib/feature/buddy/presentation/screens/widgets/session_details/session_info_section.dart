import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';

class SessionInfoSection extends StatelessWidget {
  final BuddySessionEntity session;
  const SessionInfoSection({super.key, required this.session});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Padding(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding, vertical: AppSizes.sm,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Schedule & time ───────────────────────────────
          _SectionCard(
            children: [
              _InfoRow(
                icon: Icons.schedule_rounded,
                iconColor: ColorRes.anisGreen,
                label: S.current.sessionTime,
                value: session.timeLabel,
              ),
              Divider(height: AppSizes.md, color: ColorRes.anisLine),
              _InfoRow(
                icon: Icons.calendar_today_rounded,
                iconColor: ColorRes.anisTagBlueTxt,
                label: S.current.sessionDate,
                value: _formatDate(session.startTime),
              ),
            ],
          ),
          const Sizer(height: 12),

          // ── Description ───────────────────────────────────
          Text(
            S.current.sessionDescription,
            textAlign: TextAlign.start,
            style: tt.bodyLarge?.copyWith(
              fontWeight: FontWeight.w800, color: ColorRes.anisNavy,
            ),
          ),
          const Sizer(height: 8),
          Container(
            width: double.infinity,
            padding: EdgeInsets.all(AppSizes.md),
            decoration: BoxDecoration(
              color: ColorRes.anisCardBg,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              border: Border(
                left:  BorderSide(color: ColorRes.anisGreen, width: 3),
              ),
            ),
            child: Text(
              session.description,
              textAlign: TextAlign.start,
              style: tt.bodyMedium?.copyWith(
                color: ColorRes.anisTextDark, height: 1.6,
              ),
            ),
          ),
        ],
      ),
    );
  }

  String _formatDate(DateTime dt) {
    const months = [
      'يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو',
      'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر',
    ];
    return '${dt.day} ${months[dt.month - 1]} ${dt.year}';
  }
}

// ── Shared card ────────────────────────────────────────────────────────────

class _SectionCard extends StatelessWidget {
  final List<Widget> children;
  const _SectionCard({required this.children});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.06),
            blurRadius: AppSizes.md,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      padding: EdgeInsets.all(AppSizes.md),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: children,
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  final IconData icon;
  final Color iconColor;
  final String label;
  final String value;
  const _InfoRow({
    required this.icon, required this.iconColor,
    required this.label, required this.value,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 8,
          height: AppSizes.iconSm + 8,
          decoration: BoxDecoration(
            color: iconColor.withOpacity(0.1),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          ),
          child: Icon(icon, size: AppSizes.iconXs + 2, color: iconColor),
        ),
        const Sizer(width: 10),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
              ),
              const Sizer(height: 2),
              Text(
                value,
                textAlign: TextAlign.start,
                style: tt.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w700, color: ColorRes.anisNavy,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

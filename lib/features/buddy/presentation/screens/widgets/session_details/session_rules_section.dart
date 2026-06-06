import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class SessionRulesSection extends StatelessWidget {
  final List<String> rules;
  const SessionRulesSection({super.key, required this.rules});

  @override
  Widget build(BuildContext context) {
    if (rules.isEmpty) return const SizedBox.shrink();
    final tt = Theme.of(context).textTheme;

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Title ─────────────────────────────────────
          Row(
            children: [
              Container(
                width: AppSizes.iconSm + 8,
                height: AppSizes.iconSm + 8,
                decoration: BoxDecoration(
                  color: ColorRes.anisWarningBg,
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
                child: Icon(
                  Icons.shield_outlined,
                  size: AppSizes.iconXs + 2,
                  color: ColorRes.anisGold,
                ),
              ),
              const Sizer(width: 8),
              Text(
                S.current.sessionRules,
                textAlign: TextAlign.start,
                style: tt.titleSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
              ),
            ],
          ),
          const Sizer(height: 12),

          // ── Rule items ─────────────────────────────────
          ...rules.asMap().entries.map(
                (e) => Padding(
                  padding: EdgeInsets.only(
                    bottom: e.key < rules.length - 1 ? AppSizes.sm : 0,
                  ),
                  child: _RuleItem(index: e.key + 1, text: e.value),
                ),
              ),
        ],
      ),
    );
  }
}

// ── Individual rule card ──────────────────────────────────────────────────────

class _RuleItem extends StatelessWidget {
  final int index;
  final String text;
  const _RuleItem({required this.index, required this.text});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.04),
            blurRadius: AppSizes.sm - 2,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Number badge
          Container(
            width: AppSizes.iconSm + 6,
            height: AppSizes.iconSm + 6,
            decoration: BoxDecoration(
              color: ColorRes.anisWarningBg,
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              '$index',
              style: tt.bodySmall?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisGold,
              ),
            ),
          ),
          const Sizer(width: 12),

          // Rule text
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(top: AppSizes.xs - 1),
              child: Text(
                text,
                textAlign: TextAlign.start,
                style: tt.bodyMedium?.copyWith(
                  color: ColorRes.anisTextDark,
                  height: 1.5,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

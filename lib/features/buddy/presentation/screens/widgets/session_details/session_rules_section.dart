import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

import 'rule_item.dart';

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
                width: 6,
                height: 18,
                decoration: BoxDecoration(
                  color: ColorRes.anisGreen,
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
                ),
              ),
              const Sizer(width: 8),
              Text(
                S.current.sessionRules,
                style: tt.titleSmall?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.anisNavy,
                ),
              ),
            ],
          ),
          const Sizer(height: 14),

          // ── Rule items ─────────────────────────────────
          ...rules.asMap().entries.map(
                (e) => Padding(
                  padding: EdgeInsets.only(
                    bottom: e.key < rules.length - 1 ? AppSizes.sm : 0,
                  ),
                  child: RuleItem(index: e.key + 1, text: e.value),
                ),
              ),
        ],
      ),
    );
  }
}

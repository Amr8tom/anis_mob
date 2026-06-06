import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';

/// Section 4 — attendance rules builder.
class CreateSessionRulesSection extends StatelessWidget {
  const CreateSessionRulesSection({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.sessionRules),
        const Sizer(height: 10),
        BlocBuilder<BuddyCubit, BuddyState>(
          buildWhen: (p, c) => p.createRules != c.createRules,
          builder: (context, state) {
            return SessionFormCard(
              children: [
                SessionFieldLabel(S.current.createSessionAddRule),
                const Sizer(height: 6),
                Row(
                  children: [
                    Expanded(
                      child: SessionStyledField(
                        controller: cubit.ruleCtrl,
                        hint: S.current.createSessionRuleHint,
                        onSubmitted: (_) => cubit.addRule(),
                      ),
                    ),
                    const Sizer(width: 8),
                    GestureDetector(
                      onTap: cubit.addRule,
                      child: Container(
                        width: AppSizes.iconXLarge + AppSizes.sm,
                        height: AppSizes.iconXLarge + AppSizes.sm,
                        decoration: BoxDecoration(
                          color: ColorRes.anisGreen,
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusLg),
                        ),
                        child: Icon(Icons.add_rounded,
                            size: AppSizes.iconSm, color: ColorRes.white),
                      ),
                    ),
                  ],
                ),
                if (state.createRules.isNotEmpty) ...[
                  const Sizer(height: 10),
                  ...state.createRules.asMap().entries.map(
                        (e) => _RuleChip(
                          index: e.key + 1,
                          text: e.value,
                          onRemove: () => cubit.removeRule(e.key),
                        ),
                      ),
                ],
              ],
            );
          },
        ),
      ],
    );
  }
}

class _RuleChip extends StatelessWidget {
  final int index;
  final String text;
  final VoidCallback onRemove;
  const _RuleChip({
    required this.index,
    required this.text,
    required this.onRemove,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.xs + 2),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisWarningBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(
            color: ColorRes.anisGold.withValues(alpha: 0.3), width: 1),
      ),
      child: Row(
        children: [
          Container(
            width: AppSizes.iconXs + 4,
            height: AppSizes.iconXs + 4,
            decoration: BoxDecoration(
              color: ColorRes.anisGold.withValues(alpha: 0.3),
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              '$index',
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.anisGold,
                    fontSize: 10,
                  ),
            ),
          ),
          const Sizer(width: 8),
          Expanded(
            child: Text(
              text,
              textAlign: TextAlign.start,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: ColorRes.anisTextDark,
                  ),
            ),
          ),
          GestureDetector(
            onTap: onRemove,
            child: Icon(Icons.close_rounded,
                size: AppSizes.iconXs + 2, color: ColorRes.anisHintText),
          ),
        ],
      ),
    );
  }
}

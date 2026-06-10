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

import 'rule_chip.dart';

/// Section 4 — attendance rules builder.
class CreateSessionRulesSection extends StatelessWidget {
  final TextEditingController ruleCtrl;
  final VoidCallback onAddRule;

  const CreateSessionRulesSection({
    super.key,
    required this.ruleCtrl,
    required this.onAddRule,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.sessionRules),
        const Sizer(height: 10),
        BlocBuilder<BuddyCubit, BuddyState>(
          buildWhen: (p, c) => p.createRules != c.createRules,
          builder: (context, state) {
            final cubit = context.read<BuddyCubit>();
            return SessionFormCard(
              children: [
                SessionFieldLabel(S.current.createSessionAddRule),
                const Sizer(height: 6),
                Row(
                  children: [
                    Expanded(
                      child: SessionStyledField(
                        controller: ruleCtrl,
                        hint: S.current.createSessionRuleHint,
                        onSubmitted: (_) => onAddRule(),
                      ),
                    ),
                    const Sizer(width: 8),
                    GestureDetector(
                      onTap: onAddRule,
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
                        (e) => RuleChip(
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

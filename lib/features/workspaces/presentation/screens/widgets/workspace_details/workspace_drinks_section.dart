import 'package:flutter/material.dart';
import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_drink_entity.dart';
import 'drink_row.dart';

class WorkspaceDrinksSection extends StatelessWidget {
  final List<WorkspaceDrinkEntity> drinks;
  const WorkspaceDrinksSection({super.key, required this.drinks});

  static const _drinkEmoji = <String, String>{
    'Espresso': '☕',
    'Cappuccino': '☕',
    'Latte': '🥛',
    'Americano': '☕',
    'Mocha': '🍫',
    'Tea': '🍵',
    'Hot Chocolate': '🍫',
    'Cold Brew': '🧊',
    'Orange Juice': '🍊',
    'Iced Coffee': '🧋',
  };

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.drinksMenu,
            textAlign: TextAlign.start,
            style: tt.titleSmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.anisNavy,
            ),
          ),
          Sizer(height: AppSizes.sm),
          if (drinks.isEmpty)
            CustomUI.anisEmptyState(
              context: context,
              icon: Icons.local_cafe_outlined,
              title: S.current.noData,
            )
          else
            ...drinks.map((drink) => DrinkRow(
                drink: drink, emoji: _drinkEmoji[drink.name] ?? '🥤')),
        ],
      ),
    );
  }
}

import 'package:anis/feature/workspaces/presentation/screens/widgets/workspace_details/workspace_drink_card.dart';
import 'package:flutter/material.dart';

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/workspace_drink_entity.dart';

class WorkspaceDrinksListSection extends StatelessWidget {
  final List<WorkspaceDrinkEntity> drinks;

  const WorkspaceDrinksListSection({super.key, required this.drinks});

  @override
  Widget build(BuildContext context) {
    if (drinks.isEmpty) {
      return CustomUI.anisEmptyState(
        context: context,
        icon: Icons.local_cafe_outlined,
        title: S.current.noData,
        subtitle: S.current.noData,
      );
    }

    return ListView.separated(
      physics: const BouncingScrollPhysics(),
      padding: EdgeInsets.only(bottom: AppSizes.md),
      itemBuilder: (_, index) => WorkspaceDrinkCard(drink: drinks[index]),
      separatorBuilder: (_, __) => const Sizer(height: 12),
      itemCount: drinks.length,
    );
  }
}


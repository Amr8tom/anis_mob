import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/workspace_cubit.dart';

class WorkspaceFilterChipsSection extends StatelessWidget {
  const WorkspaceFilterChipsSection({super.key});

  @override
  Widget build(BuildContext context) {
    final chips = [
      (key: 'all', label: S.current.allFilter),
      (key: 'openNow', label: S.current.openNow),
      (key: 'nearby', label: S.current.nearbyFilter),
    ];

    return SliverToBoxAdapter(
      child: BlocBuilder<WorkspaceCubit, WorkspaceState>(
        buildWhen: (p, c) => p.activeFilter != c.activeFilter,
        builder: (context, state) {
          return SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              vertical: AppSizes.sm,
            ),
            child: Row(
              textDirection: TextDirection.rtl,
              children: chips.map((chip) {
                final isActive = chip.key == state.activeFilter;
                return GestureDetector(
                  onTap: () => context.read<WorkspaceCubit>().setFilter(chip.key),
                  child: Container(
                    margin: EdgeInsets.only(left: AppSizes.sm),
                    padding: EdgeInsets.symmetric(
                      horizontal: AppSizes.md,
                      vertical: AppSizes.sm * 0.75,
                    ),
                    decoration: BoxDecoration(
                      color: isActive ? ColorRes.anisGreen : ColorRes.white,
                      borderRadius: BorderRadius.circular(
                        AppSizes.borderRadiusXLg,
                      ),
                      border: Border.all(
                        color: isActive ? ColorRes.anisGreen : ColorRes.accent,
                        width: 1,
                      ),
                    ),
                    child: Text(
                      chip.label,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                            fontWeight: FontWeight.w600,
                            color: isActive
                                ? ColorRes.white
                                : ColorRes.anisChipText,
                          ),
                    ),
                  ),
                );
              }).toList(),
            ),
          );
        },
      ),
    );
  }
}



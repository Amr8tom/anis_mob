import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../workspaces/domain/entity/workspace_entity.dart';

class WorkspaceTile extends StatelessWidget {
  final WorkspaceEntity workspace;
  final bool isSelected;
  final VoidCallback onTap;

  const WorkspaceTile({
    super.key,
    required this.workspace,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        margin: EdgeInsets.only(bottom: AppSizes.sm),
        padding: EdgeInsets.all(AppSizes.sm + 4),
        decoration: BoxDecoration(
          color: isSelected
              ? ColorRes.anisGreen.withValues(alpha: 0.07)
              : ColorRes.anisChipBg,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: isSelected ? ColorRes.anisGreen : ColorRes.anisLine,
            width: isSelected ? 1.5 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(
              Icons.location_on_rounded,
              size: AppSizes.iconSm,
              color: isSelected ? ColorRes.anisGreen : ColorRes.anisHintText,
            ),
            const Sizer(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    workspace.name,
                    textAlign: TextAlign.start,
                    style: tt.bodyMedium?.copyWith(
                      fontWeight: FontWeight.w700,
                      color:
                          isSelected ? ColorRes.anisGreen : ColorRes.anisNavy,
                    ),
                  ),
                  Text(
                    workspace.address,
                    textAlign: TextAlign.start,
                    style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
                  ),
                ],
              ),
            ),
            if (isSelected)
              Icon(Icons.check_circle_rounded,
                  size: AppSizes.iconSm, color: ColorRes.anisGreen),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';

class WorkspaceMetaItem extends StatelessWidget {
  final IconData icon;
  final String label;

  const WorkspaceMetaItem({
    super.key,
    required this.icon,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: AppSizes.iconXs, color: ColorRes.anisHintText),
        const Sizer(width: 3),
        Text(
          label,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: ColorRes.anisTextMuted,
              ),
        ),
      ],
    );
  }
}


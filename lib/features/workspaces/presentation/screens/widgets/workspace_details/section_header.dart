import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';

class SectionHeader extends StatelessWidget {
  final String label;
  final Color color;
  final IconData icon;

  const SectionHeader({
    super.key,
    required this.label,
    required this.color,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Row(
      children: [
        Icon(icon, size: AppSizes.iconSm, color: color),
        Sizer(width: AppSizes.xs),
        Text(
          label,
          style: tt.titleSmall?.copyWith(
            fontWeight: FontWeight.w700,
            color: color,
          ),
        ),
      ],
    );
  }
}

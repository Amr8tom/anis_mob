import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class SectionTitle extends StatelessWidget {
  final IconData icon;
  final String label;
  const SectionTitle({super.key, required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 8,
          height: AppSizes.iconSm + 8,
          decoration: BoxDecoration(
            color: ColorRes.anisGreen.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          ),
          child: Icon(
            icon,
            size: AppSizes.iconXs + 2,
            color: ColorRes.anisGreen,
          ),
        ),
        const Sizer(width: 8),
        Text(
          label,
          textAlign: TextAlign.start,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisNavy,
              ),
        ),
      ],
    );
  }
}

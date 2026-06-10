import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class WorkspaceDescriptionSection extends StatelessWidget {
  final String description;
  const WorkspaceDescriptionSection({super.key, required this.description});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.white,
          border: const Border(
            right: BorderSide(
              color: ColorRes.anisGreen,
              width: 4,
            ),
          ),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.04),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Text(
          description,
          textAlign: TextAlign.start,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextSecondary,
            height: 1.6,
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

/// White elevated card that wraps each create-session form section.
class SessionFormCard extends StatelessWidget {
  final List<Widget> children;
  const SessionFormCard({super.key, required this.children});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.05),
            blurRadius: AppSizes.md,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: children,
      ),
    );
  }
}

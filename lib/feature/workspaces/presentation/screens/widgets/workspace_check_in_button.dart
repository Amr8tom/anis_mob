import 'package:flutter/material.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';

class WorkspaceCheckInButton extends StatelessWidget {
  final VoidCallback? onTap;

  const WorkspaceCheckInButton({super.key, this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.xs + 2,
        ),
        decoration: BoxDecoration(
          color: ColorRes.anisGreen,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        ),
        child: Text(
          S.current.scanQrShort,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                fontWeight: FontWeight.w700,
                color: ColorRes.white,
              ),
        ),
      ),
    );
  }
}


import 'package:flutter/material.dart';

import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';

/// Public widget extracted from the private `_HeaderSkeleton` in `home_screen.dart`.
class HeaderSkeletonWidget extends StatelessWidget {
  const HeaderSkeletonWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      height: AppSizes.containerLarge,
      decoration: BoxDecoration(
        color: ColorRes.anisGreen,
        borderRadius:  BorderRadius.only(
          bottomLeft: Radius.circular(AppSizes.borderRadiusXXLg),
          bottomRight: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
    );
  }
}

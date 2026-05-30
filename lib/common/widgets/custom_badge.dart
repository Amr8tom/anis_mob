import 'package:flutter/material.dart';

import '../../core/constants/app_sizes.dart';
import '../../core/constants/colors.dart';

class CustomBadge extends StatelessWidget {
  final Color backgroundColor;
  final String badgeTitle;
  final double? top, right, bottom, left, width, borderRadius;
  final bool isPositioned;

  const CustomBadge({
    super.key,
    required this.backgroundColor,
    required this.badgeTitle,
    this.top,
    this.right,
    this.bottom,
    this.left,
    this.width,
    this.borderRadius,
    this.isPositioned=true,
  });

  @override
  Widget build(BuildContext context) {
    return isPositioned?Positioned(
      top: top,
      right: right,
      bottom: bottom,
      left: left,
      child: Container(
        padding: EdgeInsets.all(AppSizes.padding/4),
        width: width ?? AppSizes.widthcontainer / 1.5,
        decoration: BoxDecoration(
          color: backgroundColor,
          borderRadius:
              borderRadius != null
                  ? BorderRadius.all(Radius.circular(borderRadius!))
                  : BorderRadius.only(
                    topRight: Radius.circular(AppSizes.borderRadiusSm),
                    bottomRight: Radius.circular(AppSizes.borderRadiusSm),
                  ),
        ),
        child: Center(
          child: Text(
            badgeTitle,
            style: Theme.of(
              context,
            ).textTheme.bodySmall!.copyWith(color: ColorRes.white),
          ),
        ),
      ),
    ): Container(
      height: AppSizes.heightcontainer / 2.2,
      width: width ?? AppSizes.widthcontainer / 1.5,
      decoration: BoxDecoration(
        color: backgroundColor,
        borderRadius:
        borderRadius != null
            ? BorderRadius.all(Radius.circular(borderRadius!))
            : BorderRadius.only(
          topRight: Radius.circular(AppSizes.borderRadiusSm),
          bottomRight: Radius.circular(AppSizes.borderRadiusSm),
        ),
      ),
      child: Center(
        child: Text(
          badgeTitle,
          style: Theme.of(
            context,
          ).textTheme.bodySmall!.copyWith(color: ColorRes.white),
        ),
      ),
    );
  }
}

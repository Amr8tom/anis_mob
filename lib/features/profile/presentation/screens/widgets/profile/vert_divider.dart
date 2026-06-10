import 'package:flutter/material.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class VertDivider extends StatelessWidget {
  const VertDivider({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 1,
      height: AppSizes.containerSmall * 0.6,
      color: ColorRes.accent,
    );
  }
}

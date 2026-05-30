import 'package:flutter/material.dart';
import '../../core/constants/colors.dart';

import '../core/constants/app_sizes.dart';

class CustomTab extends StatelessWidget {
  final isSelected;
  final title ;
  const CustomTab({super.key, required this.title,  required this.isSelected});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: isSelected ? ColorRes.primary : ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
        border: Border.all(
          color: isSelected ? ColorRes.primary : ColorRes.grey_F707340,
          width: 1,
        ),
      ),
      width: double.infinity,
      height: AppSizes.heightcontainer / 1.3,
      child: Tab(
        child: Text(
          title,
          style: TextStyle(
            color: isSelected ? ColorRes.white : ColorRes.grey,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}

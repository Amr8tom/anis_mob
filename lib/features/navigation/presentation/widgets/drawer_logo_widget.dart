import 'package:flutter/material.dart';

import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/asset_resoures.dart';

class DrawerLogoWidget extends StatelessWidget {
  const DrawerLogoWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.padding / 2,
      ),
      child: Image.asset(
        AssetRes.ejadLogo,
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';

showDBottomSheet({required BuildContext context, required Widget body}) {
  return showModalBottomSheet(
    backgroundColor: ColorRes.primary,
    context: context,
    isScrollControlled: true,
    shape: RoundedRectangleBorder(
      borderRadius: BorderRadius.vertical(
        top: Radius.circular(AppSizes.borderRadiusLg),
      ),
    ),
    builder: (context) {
      return body;
    },
  );
}

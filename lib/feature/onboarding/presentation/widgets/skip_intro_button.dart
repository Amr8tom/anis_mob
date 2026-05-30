import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:flutter/material.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';

class SkipIntroButton extends StatelessWidget {
  const SkipIntroButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: ElevatedButton(
            onPressed: () {
              context.pushNamed(DRoutesName.verifyAccountRoute);
            },
            child: Text(
              S.current.skip,
              style: Theme.of(
                context,
              ).textTheme.titleMedium?.copyWith(color: ColorRes.yellow),
            ),
            style: ElevatedButton.styleFrom(
              backgroundColor: ColorRes.primary,
              minimumSize: Size(double.infinity, AppSizes.buttonHeight * 1.2),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.zero),
            ),
          ),
        ),
      ],
    );
  }
}

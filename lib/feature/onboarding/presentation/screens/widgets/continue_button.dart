import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:flutter/material.dart';
import '../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../generated/l10n.dart';

class ContinueButton extends StatelessWidget {
  const ContinueButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Expanded(
          child: ElevatedButton(
            onPressed: () {
              context.pushNamed(DRoutesName.verifyAccountRoute);
            },
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  S.current.continuee,
                  style: Theme.of(
                    context,
                  ).textTheme.titleMedium?.copyWith(color: ColorRes.yellow),
                ),
                const Sizer(width: 8,),
                Icon(Icons.arrow_forward_ios,color: ColorRes.yellow,size: AppSizes.ld,),
              ],
            ),
            style: ElevatedButton.styleFrom(
              backgroundColor: ColorRes.primary,
              minimumSize: Size(double.infinity, AppSizes.buttonHeight*1.2),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.zero),
            ),

          ),
        ),
      ],
    );
  }
}

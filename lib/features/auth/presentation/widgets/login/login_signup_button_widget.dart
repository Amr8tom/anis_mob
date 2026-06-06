import 'package:flutter/material.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/extentions/navigation_extension.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../generated/l10n.dart';

class LoginSignupButton extends StatelessWidget {
  const LoginSignupButton({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return SizedBox(
      width: double.infinity,
      height: AppSizes.buttonHeight,
      child: OutlinedButton.icon(
        onPressed: () => context.pushNamed(DRoutesName.userInfoRoute),
        icon: const Icon(Icons.person_add_alt_1_rounded, size: 18),
        label: Text(
          S.current.createAccount,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisGreen,
            fontWeight: FontWeight.w700,
          ),
        ),
        style: OutlinedButton.styleFrom(
          foregroundColor: ColorRes.anisGreen,
          side: const BorderSide(color: ColorRes.anisGreen, width: 1.5),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          ),
        ),
      ),
    );
  }
}

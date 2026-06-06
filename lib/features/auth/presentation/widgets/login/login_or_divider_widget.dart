import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

/// Elegant horizontal rule with a localised "OR" label — RTL-neutral.
class LoginOrDivider extends StatelessWidget {
  const LoginOrDivider({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        const Expanded(
          child: Divider(color: ColorRes.anisLine, thickness: 1),
        ),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.sm),
          child: Text(
            S.current.or,
            style: Theme.of(context).textTheme.labelSmall?.copyWith(
              color: ColorRes.anisHintText,
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
        const Expanded(
          child: Divider(color: ColorRes.anisLine, thickness: 1),
        ),
      ],
    );
  }
}

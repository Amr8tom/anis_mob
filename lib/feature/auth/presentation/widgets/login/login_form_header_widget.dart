import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

class LoginFormHeader extends StatelessWidget {
  const LoginFormHeader({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          S.current.login,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
            fontWeight: FontWeight.bold,
            color: ColorRes.white,
          ),
        ),
        const Sizer(height: 4),
        Text(
          S.current.loginWelcomeBack,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
            color: ColorRes.white.withValues(alpha: 0.50),
          ),
        ),
      ],
    );
  }
}

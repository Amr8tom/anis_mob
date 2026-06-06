import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/login/login_cubit.dart';

/// Minimalist "Continue as Guest" text button.
/// Charcoal-grey tone — premium, unobtrusive, RTL-safe.
class LoginGuestButton extends StatelessWidget {
  const LoginGuestButton({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return BlocBuilder<LoginCubit, LoginState>(
      buildWhen: (prev, curr) => prev.status != curr.status,
      builder: (context, state) {
        final isLoading = state.status.isLoggingIn || state.status.isGuest;

        return SizedBox(
          width: double.infinity,
          height: AppSizes.buttonHeight,
          child: TextButton(
            onPressed: isLoading
                ? null
                : () => context.read<LoginCubit>().continueAsGuest(),
            style: TextButton.styleFrom(
              foregroundColor: ColorRes.anisTextMuted,
              disabledForegroundColor: ColorRes.anisHintText,
              overlayColor: ColorRes.anisChipBg,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                side: const BorderSide(color: ColorRes.anisInputBorder),
              ),
            ),
            child: Text(
              S.current.continueAsGuest,
              style: tt.bodyMedium?.copyWith(
                color: ColorRes.anisTextMuted,
                fontWeight: FontWeight.w500,
                letterSpacing: 0.2,
              ),
            ),
          ),
        );
      },
    );
  }
}

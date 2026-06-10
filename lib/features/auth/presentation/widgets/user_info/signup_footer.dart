import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/generated/l10n.dart';
import 'package:anis/core/utils/enums/general_status.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'signup_back_button.dart';

class SignUpFooter extends StatelessWidget {
  final VoidCallback onNext;
  final VoidCallback onBack;

  const SignUpFooter({
    super.key,
    required this.onNext,
    required this.onBack,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) => p.step != c.step || p.status != c.status,
      builder: (_, state) {
        final isLoading = state.status.isLoading;
        final isLast = state.step == UserInfoCubit.totalSteps - 1;

        return Container(
          color: ColorRes.white,
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.sm,
            AppSizes.padding,
            AppSizes.ld,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // ── Action row ───────────────────────────────
              Row(
                children: [
                  // Back button (only shown on step > 0)
                  if (state.step > 0) ...[
                    SignUpBackButton(
                      onTap: isLoading ? null : onBack,
                    ),
                    const Sizer(width: 12),
                  ],

                  // Next / Create account button
                  Expanded(
                    child: SizedBox(
                      height: AppSizes.buttonHeight,
                      child: ElevatedButton(
                        onPressed: isLoading ? null : onNext,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: ColorRes.anisGreen,
                          foregroundColor: ColorRes.white,
                          disabledBackgroundColor:
                              ColorRes.anisGreen.withValues(alpha: 0.55),
                          elevation: 0,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(
                              AppSizes.borderRadiusMd,
                            ),
                          ),
                        ),
                        child: isLoading
                            ? const SizedBox(
                                width: 22,
                                height: 22,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2.5,
                                  valueColor: AlwaysStoppedAnimation<Color>(
                                    ColorRes.white,
                                  ),
                                ),
                              )
                            : Text(
                                isLast
                                    ? S.current.createAccount
                                    : S.current.continuee,
                                style: tt.titleSmall?.copyWith(
                                  fontWeight: FontWeight.w700,
                                  color: ColorRes.white,
                                  fontSize: 15,
                                ),
                              ),
                      ),
                    ),
                  ),
                ],
              ),

              const Sizer(height: 16),

              // ── Already have an account ──────────────────
              GestureDetector(
                onTap: () =>
                    context.pushReplacementNamed(DRoutesName.loginRoute),
                child: Wrap(
                  alignment: WrapAlignment.center,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    Text(
                      '${S.current.alreadyHaveAccount} ',
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisTextMuted,
                        fontSize: 13,
                      ),
                    ),
                    Text(
                      S.current.signIn,
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisGreen,
                        fontWeight: FontWeight.w700,
                        fontSize: 13,
                        decoration: TextDecoration.underline,
                        decorationColor: ColorRes.anisGreen,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/custom_ui.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/service_locator/service_locator.dart';
import 'package:anis/core/utils/enums/general_status.dart';
import 'package:anis/generated/l10n.dart';

import '../controller/user_info/user_info_cubit.dart';
import '../widgets/user_info/step_account_widget.dart';

class UserInfoScreen extends StatelessWidget {
  const UserInfoScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => serviceLocator<UserInfoCubit>(),
      child: const _UserInfoView(),
    );
  }
}

class _UserInfoView extends StatelessWidget {
  const _UserInfoView();

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UserInfoCubit>();

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.dark,
      child: BlocListener<UserInfoCubit, UserInfoState>(
        listenWhen: (p, c) =>
            p.stepError != c.stepError || p.status != c.status,
        listener: (ctx, state) {
          if (state.stepError.isNotEmpty) {
            CustomUI.snackBarFailure(context: ctx, message: state.stepError);
            cubit.clearStepError();
          }
          if (state.status.isSuccess) {
            ctx.pushReplacementNamed(
              DRoutesName.profileCompletionRoute,
              arguments: const {'afterSignup': true},
            );
          } else if (state.status.isError) {
            CustomUI.snackBarFailure(context: ctx, message: state.errorMessage);
            cubit.resetStatus();
          }
        },
        child: Scaffold(
          backgroundColor: ColorRes.white,
          body: Column(
            children: [
              // ── Green hero header ─────────────────────────
              const _SignUpHeader(),

              // ── Step progress dots ────────────────────────
              const _StepProgress(),

              // ── Page content ──────────────────────────────
              Expanded(
                child: PageView(
                  controller: cubit.pageController,
                  physics: const NeverScrollableScrollPhysics(),
                  children: [
                    StepAccountWidget(
                      passwordController: cubit.passwordController,
                      confirmPasswordController:
                          cubit.confirmPasswordController,
                    ),
                  ],
                ),
              ),

              // ── Footer: back + next ───────────────────────
              const _SignUpFooter(),
            ],
          ),
        ),
      ),
    );
  }
}

// ── Green hero header ─────────────────────────────────────────────────────────

class _SignUpHeader extends StatelessWidget {
  const _SignUpHeader();

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      width: double.infinity,
      color: ColorRes.anisGreen,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        MediaQuery.of(context).padding.top + 16.h,
        AppSizes.padding,
        20.h,
      ),
      child: Row(
        children: [
          // App icon
          Container(
            width: 40.w,
            height: 40.w,
            decoration: BoxDecoration(
              color: ColorRes.white,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisNavy.withValues(alpha: 0.18),
                  blurRadius: 10,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            padding: EdgeInsets.all(AppSizes.xs),
            child: Image.asset(AssetRes.logo, fit: BoxFit.contain),
          ),
          const Sizer(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                S.current.register,
                style: tt.titleMedium?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.white,
                  fontSize: 17,
                ),
              ),
              Text(
                S.current.appTagline,
                style: tt.bodySmall?.copyWith(
                  color: ColorRes.white.withValues(alpha: 0.72),
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

// ── Step progress indicator ───────────────────────────────────────────────────

class _StepProgress extends StatelessWidget {
  const _StepProgress();

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) => p.step != c.step,
      builder: (_, state) {
        final step = state.step;
        final total = UserInfoCubit.totalSteps;
        final tt = Theme.of(context).textTheme;

        return Container(
          color: ColorRes.white,
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.md,
            AppSizes.padding,
            AppSizes.sm,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Progress bar
              ClipRRect(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
                child: LinearProgressIndicator(
                  value: (step + 1) / total,
                  minHeight: 4,
                  backgroundColor: ColorRes.anisLine,
                  valueColor: const AlwaysStoppedAnimation<Color>(
                    ColorRes.anisGreen,
                  ),
                ),
              ),
              const Sizer(height: 8),
              Text(
                S.current.stepIndicator(
                  step + 1,
                  total,
                  S.current.yourAccount,
                ),
                style: tt.labelSmall?.copyWith(
                  color: ColorRes.anisTextMuted,
                  letterSpacing: 0.2,
                  fontSize: 12,
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

// ── Footer ────────────────────────────────────────────────────────────────────

class _SignUpFooter extends StatelessWidget {
  const _SignUpFooter();

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UserInfoCubit>();
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
                    _BackButton(
                      onTap: isLoading ? null : cubit.backStep,
                    ),
                    const Sizer(width: 12),
                  ],

                  // Next / Create account button
                  Expanded(
                    child: SizedBox(
                      height: AppSizes.buttonHeight,
                      child: ElevatedButton(
                        onPressed: isLoading ? null : cubit.nextStep,
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

// ── Back button ───────────────────────────────────────────────────────────────

class _BackButton extends StatelessWidget {
  final VoidCallback? onTap;
  const _BackButton({this.onTap});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: AppSizes.buttonHeight,
      height: AppSizes.buttonHeight,
      child: TextButton(
        onPressed: onTap,
        style: TextButton.styleFrom(
          foregroundColor: ColorRes.anisTextMuted,
          overlayColor: ColorRes.anisChipBg,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            side: const BorderSide(color: ColorRes.anisInputBorder),
          ),
          padding: EdgeInsets.zero,
        ),
        child: const Icon(
          Icons.arrow_back_ios_new_rounded,
          size: 18,
          color: ColorRes.anisTextMuted,
        ),
      ),
    );
  }
}

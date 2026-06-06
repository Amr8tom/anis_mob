import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/custom_ui.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/utils/validators.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/login/login_cubit.dart';
import 'login_button_widget.dart';
import 'login_dark_field_widget.dart';
import 'login_guest_button_widget.dart';
import 'login_or_divider_widget.dart';
import 'login_signup_button_widget.dart';

class LoginForm extends StatelessWidget {
  const LoginForm({super.key});

  void _handleState(BuildContext context, LoginState state) {
    // Authenticated user
    if (state.status.isLoggedIn) {
      context.pushReplacementNamed(DRoutesName.navigationMenuRoute);
      return;
    }
    // Guest user — navigate to home but as guest
    if (state.status.isGuest) {
      context.pushReplacementNamed(DRoutesName.navigationMenuRoute);
      return;
    }
    if (state.status.isError) {
      CustomUI.snackBarFailure(
        context: context,
        message: state.loginErrorMassage ?? S.current.generalError,
      );
      context.read<LoginCubit>().resetStatus();
    }
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<LoginCubit>();

    return BlocListener<LoginCubit, LoginState>(
      listener: _handleState,
      child: Container(
        width: double.infinity,
        color: ColorRes.white,
        padding: EdgeInsets.fromLTRB(
          AppSizes.padding,
          28.h,
          AppSizes.padding,
          24.h,
        ),
        child: Form(
          key: cubit.formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Sizer(height: 28),

              LoginDarkField(
                controller: cubit.phoneController,
                label: S.current.phoneNumber,
                hint: S.current.phoneHint,
                prefixIcon: Icons.phone_outlined,
                keyboardType: TextInputType.phone,
                validator: Validators.phone,
              ),

              const Sizer(height: 16),

              // ── Password ──────────────────────────────────────
              BlocBuilder<LoginCubit, LoginState>(
                buildWhen: (prev, curr) =>
                    prev.isPasswordHidden != curr.isPasswordHidden,
                builder: (_, state) => LoginDarkField(
                  controller: cubit.passwordController,
                  label: S.current.password,
                  hint: S.current.passwordHint,
                  prefixIcon: Icons.lock_outline_rounded,
                  obscure: state.isPasswordHidden,
                  validator: Validators.password,
                  suffixIcon: GestureDetector(
                    onTap: cubit.togglePasswordVisibility,
                    child: Icon(
                      state.isPasswordHidden
                          ? Icons.visibility_off_outlined
                          : Icons.visibility_outlined,
                      color: ColorRes.anisTextSecondary,
                      size: 20.sp,
                    ),
                  ),
                ),
              ),

              const Sizer(height: 12),

              const Sizer(height: 28),

              /// ── Login button ──────────────────────────────────
              BlocBuilder<LoginCubit, LoginState>(
                buildWhen: (prev, curr) => prev.status != curr.status,
                builder: (_, state) => LoginButton(
                  isLoading: state.status.isLoggingIn,
                  onTap: state.status.isLoggingIn ? null : cubit.login,
                ),
              ),

              const Sizer(height: 12),

              const LoginSignupButton(),

              const Sizer(height: 20),

              // ── OR divider ────────────────────────────────────
              const LoginOrDivider(),

              const Sizer(height: 20),

              // ── Continue as Guest ─────────────────────────────
              const LoginGuestButton(),

              const Sizer(height: 24),

              // ── Privacy link ──────────────────────────────────
              Center(
                child: Wrap(
                  alignment: WrapAlignment.center,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    Text(
                      '${S.current.byLoggingInYouAgree} ',
                      style: Theme.of(context).textTheme.labelSmall?.copyWith(
                            color: ColorRes.anisTextSecondary,
                          ),
                    ),
                    GestureDetector(
                      onTap: () => context.pushNamed(
                        DRoutesName.termsAndConditionRoute,
                      ),
                      child: Text(
                        S.current.privacyPolicy,
                        style: Theme.of(context).textTheme.labelSmall?.copyWith(
                              color: ColorRes.anisGreen,
                              fontWeight: FontWeight.w700,
                              decoration: TextDecoration.underline,
                              decorationColor: ColorRes.anisGreen,
                            ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

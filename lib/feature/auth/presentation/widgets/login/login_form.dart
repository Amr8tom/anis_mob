import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/custom_ui.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/utils/validators.dart';
import 'package:anis/generated/l10n.dart';

import '../../../../../common/widgets/dialogs/show_custom_pop_up.dart';
import '../../controller/login/login_cubit.dart';
import 'login_button_widget.dart';
import 'login_dark_field_widget.dart';
import 'login_form_header_widget.dart';

class LoginForm extends StatelessWidget {
  const LoginForm({super.key});

  void _handleState(BuildContext context, LoginState state) {
    if (state.status.isLoggedIn) {
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
        decoration: BoxDecoration(
          color: ColorRes.anisAuthContainer,
          borderRadius: BorderRadius.only(
            topLeft:  Radius.circular(AppSizes.borderRadiusXXLg),
            topRight: Radius.circular(AppSizes.borderRadiusXXLg),
          ),
          border: Border(
            top: BorderSide(
              color: ColorRes.anisGreen.withValues(alpha: 0.20),
              width: 1,
            ),
          ),
        ),
        padding: EdgeInsets.fromLTRB(AppSizes.padding, 28.h, AppSizes.padding, 0),
        child: Form(
          key: cubit.formKey,
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const LoginFormHeader(),
                const Sizer(height: 32),

                // Email field
                LoginDarkField(
                  controller: cubit.usernameController,
                  label: S.current.typeYourEmail,
                  hint: 'you@university.edu.eg',
                  prefixIcon: Icons.email_outlined,
                  validator: Validators.email,
                ),

                const Sizer(height: 16),

                // Password field
                BlocBuilder<LoginCubit, LoginState>(
                  buildWhen: (prev, curr) =>
                      prev.isPasswordHidden != curr.isPasswordHidden,
                  builder: (_, state) => LoginDarkField(
                    controller: cubit.passwordController,
                    label: S.current.password,
                    hint: '••••••••',
                    prefixIcon: Icons.lock_outline_rounded,
                    obscure: state.isPasswordHidden,
                    validator: Validators.password,
                    suffixIcon: GestureDetector(
                      onTap: cubit.togglePasswordVisibility,
                      child: Icon(
                        state.isPasswordHidden
                            ? Icons.visibility_off_outlined
                            : Icons.visibility_outlined,
                        color: ColorRes.white.withValues(alpha: 0.45),
                        size: 20.sp,
                      ),
                    ),
                  ),
                ),

                const Sizer(height: 16),

                // Forgot password
                Align(
                  alignment: Alignment.centerLeft,
                  child: GestureDetector(
                    onTap: () => showOTPPopUp(
                      context: context,
                      email: cubit.usernameController.text.trim(),
                    ),
                    child: Text(
                      S.current.forgetPassword,
                      style: Theme.of(context).textTheme.bodySmall?.copyWith(
                        fontWeight: FontWeight.w600,
                        color: ColorRes.anisGreen,
                      ),
                    ),
                  ),
                ),

                const Sizer(height: 32),

                // Login button
                BlocBuilder<LoginCubit, LoginState>(
                  buildWhen: (prev, curr) => prev.status != curr.status,
                  builder: (_, state) => LoginButton(
                    isLoading: state.status.isLoggingIn,
                    onTap: state.status.isLoggingIn ? null : cubit.login,
                  ),
                ),

                const Sizer(height: 4),

                // Privacy policy
                Center(
                  child: Wrap(
                    alignment: WrapAlignment.center,
                    crossAxisAlignment: WrapCrossAlignment.center,
                    children: [
                      Text(
                        '${S.current.byLoggingInYouAgree} ',
                        style: Theme.of(context).textTheme.labelSmall?.copyWith(
                          color: ColorRes.white.withValues(alpha: 0.50),
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

                const Sizer(height: 16),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../common/custom_ui.dart';
import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/extentions/navigation_extension.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../core/service_locator/service_locator.dart';
import '../../../../../core/utils/validators.dart';
import '../../../../../generated/l10n.dart';
import '../../../../notifications/presentation/controller/notifications_cubit.dart';
import '../../controller/login/login_cubit.dart';
import 'login_action_buttons_section.dart';
import 'login_dark_field_widget.dart';

class LoginFormSection extends StatefulWidget {
  const LoginFormSection({super.key});

  @override
  State<LoginFormSection> createState() => _LoginFormSectionState();
}

class _LoginFormSectionState extends State<LoginFormSection> {
  late final TextEditingController _phoneController;
  late final TextEditingController _passwordController;
  late final GlobalKey<FormState> _formKey;

  @override
  void initState() {
    super.initState();
    _phoneController = TextEditingController();
    _passwordController = TextEditingController();
    _formKey = GlobalKey<FormState>();
  }

  @override
  void dispose() {
    _phoneController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleState(BuildContext context, LoginState state) {
    if (state.status.isLoggedIn) {
      // Register this device only if notification permission is already on.
      // The settings screen owns the educational permission prompt.
      serviceLocator<NotificationsCubit>().syncTokenIfPermissionGranted();
      context.pushReplacementNamed(
        state.profileCompleted
            ? DRoutesName.navigationMenuRoute
            : DRoutesName.profileCompletionRoute,
        arguments: state.profileCompleted ? null : const {'afterSignup': true},
      );
      return;
    }
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

  void _onSubmit() {
    if (_formKey.currentState?.validate() ?? false) {
      context.read<LoginCubit>().login(
            phoneNumber: _phoneController.text.trim(),
            password: _passwordController.text.trim(),
          );
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
          28,
          AppSizes.padding,
          24,
        ),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Sizer(height: 28),

              LoginDarkField(
                controller: _phoneController,
                label: S.current.phoneNumber,
                hint: S.current.phoneHint,
                prefixIcon: Icons.phone_outlined,
                keyboardType: TextInputType.text,
                textDirection: TextDirection.ltr,
                inputFormatters: [
                  FilteringTextInputFormatter.allow(RegExp(r'[0-9+]')),
                ],
                validator: Validators.phone,
              ),

              const Sizer(height: 16),

              // ── Password ──────────────────────────────────────
              BlocBuilder<LoginCubit, LoginState>(
                buildWhen: (prev, curr) =>
                    prev.isPasswordHidden != curr.isPasswordHidden,
                builder: (_, state) => LoginDarkField(
                  controller: _passwordController,
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
                      size: 20,
                    ),
                  ),
                ),
              ),

              const Sizer(height: 28),

              LoginActionButtonsSection(
                onSubmit: _onSubmit,
              ),
            ],
          ),
        ),
      ),
    );
  }
}

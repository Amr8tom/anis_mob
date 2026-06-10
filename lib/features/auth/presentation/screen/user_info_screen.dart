import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/custom_ui.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/service_locator/service_locator.dart';
import 'package:anis/core/utils/enums/general_status.dart';
import 'package:anis/core/constants/colors.dart';

import '../controller/user_info/user_info_cubit.dart';
import '../widgets/user_info/step_account_widget.dart';
import '../widgets/user_info/signup_header.dart';
import '../widgets/user_info/signup_step_progress.dart';
import '../widgets/user_info/signup_footer.dart';

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

class _UserInfoView extends StatefulWidget {
  const _UserInfoView();

  @override
  State<_UserInfoView> createState() => _UserInfoViewState();
}

class _UserInfoViewState extends State<_UserInfoView> {
  late final PageController _pageController;
  late final TextEditingController _passwordController;
  late final TextEditingController _confirmPasswordController;

  @override
  void initState() {
    super.initState();
    _pageController = PageController();
    _passwordController = TextEditingController();
    _confirmPasswordController = TextEditingController();
  }

  @override
  void dispose() {
    _pageController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _onNext(UserInfoCubit cubit) {
    cubit.nextStep(
      password: _passwordController.text,
      confirmPassword: _confirmPasswordController.text,
    );
  }

  void _onBack(UserInfoCubit cubit) {
    cubit.backStep();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UserInfoCubit>();

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.dark,
      child: BlocListener<UserInfoCubit, UserInfoState>(
        listenWhen: (p, c) =>
            p.stepError != c.stepError || p.status != c.status || p.step != c.step,
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

          if (_pageController.hasClients && _pageController.page?.round() != state.step) {
            _pageController.animateToPage(
              state.step,
              duration: const Duration(milliseconds: 380),
              curve: Curves.easeInOutCubic,
            );
          }
        },
        child: Scaffold(
          backgroundColor: ColorRes.white,
          body: Column(
            children: [
              // ── Green hero header ─────────────────────────
              const SignUpHeader(),

              // ── Step progress dots ────────────────────────
              const SignUpStepProgress(),

              // ── Page content ──────────────────────────────
              Expanded(
                child: PageView(
                  controller: _pageController,
                  physics: const NeverScrollableScrollPhysics(),
                  children: [
                    StepAccountWidget(
                      passwordController: _passwordController,
                      confirmPasswordController: _confirmPasswordController,
                    ),
                  ],
                ),
              ),

              // ── Footer: back + next ───────────────────────
              SignUpFooter(
                onNext: () => _onNext(cubit),
                onBack: () => _onBack(cubit),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

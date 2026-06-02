import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/custom_ui.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/service_locator/service_locator.dart';
import 'package:anis/core/utils/enums/general_status.dart';
import 'package:anis/generated/l10n.dart';

import '../controller/user_info/user_info_cubit.dart';
import '../widgets/user_info/step_email_password_widget.dart';
import '../widgets/user_info/step_footer_widget.dart';
import '../widgets/user_info/step_gender_avatar_widget.dart';
import '../widgets/user_info/step_header_widget.dart';
import '../widgets/user_info/step_name_widget.dart';
import '../widgets/user_info/step_university_widget.dart';

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

  List<({IconData icon, String label})> _stepMeta(BuildContext context) => [
        (icon: Icons.person_outline_rounded,  label: S.current.yourName),
        (icon: Icons.school_outlined,         label: S.current.yourAcademicInfo),
        (icon: Icons.wc_rounded,              label: S.current.yourPersonality),
        (icon: Icons.lock_outline_rounded,    label: S.current.yourAccount),
      ];

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UserInfoCubit>();

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.light,
      child: BlocListener<UserInfoCubit, UserInfoState>(
        listenWhen: (prev, curr) =>
            prev.stepError != curr.stepError || prev.status != curr.status,
        listener: (ctx, state) {
          if (state.stepError.isNotEmpty) {
            CustomUI.snackBarFailure(context: ctx, message: state.stepError);
            cubit.clearStepError();
          }
          if (state.status.isSuccess) {
            ctx.pushReplacementNamed(DRoutesName.loginRoute);
          } else if (state.status.isError) {
            CustomUI.snackBarFailure(context: ctx, message: state.errorMessage);
            cubit.resetStatus();
          }
        },
        child: BlocBuilder<UserInfoCubit, UserInfoState>(
          buildWhen: (prev, curr) => prev.step != curr.step,
          builder: (ctx, state) {
            return Scaffold(
              backgroundColor: ColorRes.transparent,
              body: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      ColorRes.anisAuthBgTop,
                      ColorRes.anisAuthBgMid,
                      ColorRes.anisAuthBgBottom,
                    ],
                  ),
                ),
                child: SafeArea(
                  child: Column(
                    children: [
                      StepHeaderWidget(step: state.step, meta: _stepMeta(ctx)),
                      Expanded(
                        child: PageView(
                          controller: cubit.pageController,
                          physics: const NeverScrollableScrollPhysics(),
                          children: [
                            const StepNameWidget(),
                            const StepUniversityWidget(),
                            const StepGenderAvatarWidget(),
                            StepEmailPasswordWidget(
                              passwordController: cubit.passwordController,
                              confirmPasswordController: cubit.confirmPasswordController,
                            ),
                          ],
                        ),
                      ),
                      StepFooterWidget(
                        step: state.step,
                        totalSteps: UserInfoCubit.totalSteps,
                        onNext: cubit.nextStep,
                        onBack: cubit.backStep,
                      ),
                    ],
                  ),
                ),
              ),
            );
          },
        ),
      ),
    );
  }
}

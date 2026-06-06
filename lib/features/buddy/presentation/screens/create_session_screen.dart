import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controller/buddy_cubit.dart';
import 'widgets/create_session/create_session_basic_info_section.dart';
import 'widgets/create_session/create_session_gift_section.dart';
import 'widgets/create_session/create_session_rules_section.dart';
import 'widgets/create_session/create_session_schedule_section.dart';
import 'widgets/create_session/create_session_submit_button.dart';
import 'widgets/create_session/create_session_workspace_section.dart';

/// Thin stateless shell — all logic lives in [BuddyCubit].
class CreateSessionScreen extends StatelessWidget {
  const CreateSessionScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();
    return BlocListener<BuddyCubit, BuddyState>(
      listenWhen: (previous, current) =>
          previous.createStatus != current.createStatus,
      listener: (context, state) {
        if (state.createStatus == BuddyActionStatus.success) {
          Navigator.of(context).pop();
        }
      },
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        appBar: DAppBar(
          showBackArrow: true,
          title: S.current.createSessionTitle,
        ),
        body: Form(
          key: cubit.createFormKey,
          child: SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            padding: EdgeInsets.fromLTRB(
              AppSizes.padding,
              AppSizes.md,
              AppSizes.padding,
              AppSizes.xl * 2,
            ),
            child: const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CreateSessionBasicInfoSection(),
                Sizer(height: 20),
                CreateSessionScheduleSection(),
                Sizer(height: 20),
                CreateSessionWorkspaceSection(),
                Sizer(height: 20),
                CreateSessionRulesSection(),
                Sizer(height: 20),
                CreateSessionGiftSection(),
                Sizer(height: 28),
                CreateSessionSubmitButton(),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controller/buddy_cubit.dart';
import 'widgets/create_session/create_session_form_section.dart';

/// Thin stateless shell — all logic lives in [BuddyCubit].
class CreateSessionScreen extends StatelessWidget {
  const CreateSessionScreen({super.key});

  @override
  Widget build(BuildContext context) {
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
        body: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.md,
            AppSizes.padding,
            AppSizes.xl * 2,
          ),
          child: const CreateSessionFormSection(),
        ),
      ),
    );
  }
}

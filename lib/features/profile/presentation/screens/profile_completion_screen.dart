import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../controller/profile_completion_cubit.dart';
import 'widgets/profile_completion/profile_completion_footer.dart';
import 'widgets/profile_completion/profile_completion_form.dart';
import 'widgets/profile_completion/profile_completion_header.dart';

class ProfileCompletionScreen extends StatelessWidget {
  final bool afterSignup;

  const ProfileCompletionScreen({
    super.key,
    required this.afterSignup,
  });

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ProfileCompletionCubit, ProfileCompletionState>(
      listenWhen: (previous, current) =>
          previous.status != current.status ||
          previous.errorMessage != current.errorMessage,
      listener: (context, state) => _handleState(context, state, afterSignup),
      builder: (context, state) {
        final cubit = context.read<ProfileCompletionCubit>();
        final isSaving = state.status == ProfileCompletionStatus.saving;
        final isLoading = state.status == ProfileCompletionStatus.loading ||
            state.status == ProfileCompletionStatus.initial;

        return Scaffold(
          backgroundColor: ColorRes.white,
          body: SafeArea(
            child: Column(
              children: [
                ProfileCompletionHeader(
                  percentage: state.profile?.profileCompletionPercentage ?? 0,
                  onSkip: isSaving ? null : cubit.saveDraftAndContinue,
                ),
                Expanded(
                  child: isLoading
                      ? const Center(
                          child: CircularProgressIndicator(
                            color: ColorRes.anisGreen,
                          ),
                        )
                      : ProfileCompletionForm(state: state),
                ),
                ProfileCompletionFooter(
                  isSaving: isSaving,
                  onSave: cubit.saveProfile,
                  onSkip: isSaving ? null : cubit.saveDraftAndContinue,
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _handleState(
    BuildContext context,
    ProfileCompletionState state,
    bool afterSignup,
  ) {
    if (state.status == ProfileCompletionStatus.success ||
        state.status == ProfileCompletionStatus.skipped) {
      if (afterSignup) {
        context.pushNamedAndRemoveUntil(
          DRoutesName.navigationMenuRoute,
          predicate: (_) => false,
        );
      } else {
        Navigator.of(context).pop(true);
      }
      return;
    }

    final error = state.errorMessage;
    if (error == null || error.isEmpty) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(error),
        backgroundColor: ColorRes.anisErrorRed,
        behavior: SnackBarBehavior.floating,
      ),
    );
    context.read<ProfileCompletionCubit>().clearError();
  }
}

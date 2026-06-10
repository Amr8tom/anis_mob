import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../controller/profile_completion_cubit.dart';
import 'widgets/profile_completion/profile_completion_footer.dart';
import 'widgets/profile_completion/profile_completion_form.dart';
import 'widgets/profile_completion/profile_completion_header.dart';

class ProfileCompletionScreen extends StatefulWidget {
  final bool afterSignup;

  const ProfileCompletionScreen({
    super.key,
    required this.afterSignup,
  });

  @override
  State<ProfileCompletionScreen> createState() => _ProfileCompletionScreenState();
}

class _ProfileCompletionScreenState extends State<ProfileCompletionScreen> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _emailCtrl;
  late final TextEditingController _universityCtrl;
  late final TextEditingController _studyFieldCtrl;
  late final TextEditingController _interestCtrl;

  @override
  void initState() {
    super.initState();
    final profile = context.read<ProfileCompletionCubit>().state.profile;
    _emailCtrl = TextEditingController(text: profile?.email ?? '');
    _universityCtrl = TextEditingController(text: profile?.university ?? '');
    _studyFieldCtrl = TextEditingController(text: profile?.studyField ?? '');
    _interestCtrl = TextEditingController();
  }

  @override
  void dispose() {
    _emailCtrl.dispose();
    _universityCtrl.dispose();
    _studyFieldCtrl.dispose();
    _interestCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ProfileCompletionCubit, ProfileCompletionState>(
      listenWhen: (previous, current) =>
          previous.status != current.status ||
          previous.errorMessage != current.errorMessage,
      listener: (context, state) => _handleState(context, state, widget.afterSignup),
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
                  onSkip: isSaving ? null : () => cubit.saveDraftAndContinue(
                    email: _emailCtrl.text,
                    university: _universityCtrl.text,
                    studyField: _studyFieldCtrl.text,
                  ),
                ),
                Expanded(
                  child: isLoading
                      ? const Center(
                          child: CircularProgressIndicator(
                            color: ColorRes.anisGreen,
                          ),
                        )
                      : ProfileCompletionForm(
                          state: state,
                          formKey: _formKey,
                          emailController: _emailCtrl,
                          universityController: _universityCtrl,
                          studyFieldController: _studyFieldCtrl,
                          interestController: _interestCtrl,
                        ),
                ),
                ProfileCompletionFooter(
                  isSaving: isSaving,
                  onSave: () {
                    if (_formKey.currentState?.validate() ?? false) {
                      cubit.saveProfile(
                        email: _emailCtrl.text,
                        university: _universityCtrl.text,
                        studyField: _studyFieldCtrl.text,
                      );
                    }
                  },
                  onSkip: isSaving ? null : () => cubit.saveDraftAndContinue(
                    email: _emailCtrl.text,
                    university: _universityCtrl.text,
                    studyField: _studyFieldCtrl.text,
                  ),
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
    if (state.status == ProfileCompletionStatus.ready && state.profile != null) {
      if (_emailCtrl.text.isEmpty && state.profile!.email.isNotEmpty) {
        _emailCtrl.text = state.profile!.email;
      }
      if (_universityCtrl.text.isEmpty && state.profile!.university.isNotEmpty) {
        _universityCtrl.text = state.profile!.university;
      }
      if (_studyFieldCtrl.text.isEmpty && state.profile!.studyField.isNotEmpty) {
        _studyFieldCtrl.text = state.profile!.studyField;
      }
    }

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

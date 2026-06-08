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
    return Scaffold(
      backgroundColor: ColorRes.white,
      body: SafeArea(
        child: _ProfileCompletionContent(afterSignup: afterSignup),
      ),
    );
  }
}

class _ProfileCompletionContent extends StatefulWidget {
  final bool afterSignup;

  const _ProfileCompletionContent({required this.afterSignup});

  @override
  State<_ProfileCompletionContent> createState() => _ProfileCompletionContentState();
}

class _ProfileCompletionContentState extends State<_ProfileCompletionContent> {
  late final GlobalKey<FormState> _formKey;
  late final TextEditingController _emailController;
  late final TextEditingController _universityController;
  late final TextEditingController _studyFieldController;
  late final TextEditingController _interestController;
  bool _isInitialized = false;

  @override
  void initState() {
    super.initState();
    _formKey = GlobalKey<FormState>();
    _emailController = TextEditingController();
    _universityController = TextEditingController();
    _studyFieldController = TextEditingController();
    _interestController = TextEditingController();
  }

  @override
  void dispose() {
    _emailController.dispose();
    _universityController.dispose();
    _studyFieldController.dispose();
    _interestController.dispose();
    super.dispose();
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

  void _onSave(ProfileCompletionCubit cubit) {
    if (_formKey.currentState?.validate() ?? false) {
      cubit.saveProfile(
        email: _emailController.text.trim(),
        university: _universityController.text.trim(),
        studyField: _studyFieldController.text.trim(),
      );
    }
  }

  void _onSkip(ProfileCompletionCubit cubit) {
    cubit.saveDraftAndContinue(
      email: _emailController.text.trim(),
      university: _universityController.text.trim(),
      studyField: _studyFieldController.text.trim(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<ProfileCompletionCubit, ProfileCompletionState>(
      listenWhen: (previous, current) =>
          previous.status != current.status ||
          previous.errorMessage != current.errorMessage ||
          previous.profile != current.profile,
      listener: (context, state) {
        _handleState(context, state, widget.afterSignup);

        if (state.status == ProfileCompletionStatus.ready &&
            state.profile != null &&
            !_isInitialized) {
          _emailController.text = state.profile!.email;
          _universityController.text = state.profile!.university;
          _studyFieldController.text = state.profile!.studyField;
          _isInitialized = true;
        }
      },
      builder: (context, state) {
        final cubit = context.read<ProfileCompletionCubit>();
        final isSaving = state.status == ProfileCompletionStatus.saving;
        final isLoading = state.status == ProfileCompletionStatus.loading ||
            state.status == ProfileCompletionStatus.initial;

        return Column(
          children: [
            ProfileCompletionHeader(
              percentage: state.profile?.profileCompletionPercentage ?? 0,
              onSkip: isSaving ? null : () => _onSkip(cubit),
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
                      emailController: _emailController,
                      universityController: _universityController,
                      studyFieldController: _studyFieldController,
                      interestController: _interestController,
                    ),
            ),
            ProfileCompletionFooter(
              isSaving: isSaving,
              onSave: () => _onSave(cubit),
              onSkip: isSaving ? null : () => _onSkip(cubit),
            ),
          ],
        );
      },
    );
  }
}

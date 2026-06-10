import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/profile_completion_cubit.dart';
import 'section_label.dart';
import 'gender_selector.dart';
import 'interest_suggestions.dart';
import 'profile_text_field.dart';

class ProfileCompletionForm extends StatelessWidget {
  final ProfileCompletionState state;
  final GlobalKey<FormState> formKey;
  final TextEditingController emailController;
  final TextEditingController universityController;
  final TextEditingController studyFieldController;
  final TextEditingController interestController;

  const ProfileCompletionForm({
    super.key,
    required this.state,
    required this.formKey,
    required this.emailController,
    required this.universityController,
    required this.studyFieldController,
    required this.interestController,
  });

  void _addCustomInterest(BuildContext context) {
    final interest = interestController.text.trim();
    if (interest.isEmpty) return;
    context.read<ProfileCompletionCubit>().addSuggestedInterest(interest);
    interestController.clear();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<ProfileCompletionCubit>();

    return Form(
      key: formKey,
      child: ListView(
        physics: const BouncingScrollPhysics(),
        padding: EdgeInsets.fromLTRB(
          AppSizes.padding,
          AppSizes.md,
          AppSizes.padding,
          AppSizes.xl,
        ),
        children: [
          ProfileTextField(
            controller: emailController,
            label: S.current.email,
            hint: S.current.profileEmailHint,
            icon: Icons.alternate_email_rounded,
            keyboardType: TextInputType.emailAddress,
            validator: (value) {
              final email = value?.trim() ?? '';
              if (email.isEmpty || !email.contains('@')) {
                return S.current.invalidEmail;
              }
              return null;
            },
          ),
          const Sizer(height: 16),
          ProfileTextField(
            controller: universityController,
            label: S.current.university,
            hint: S.current.profileUniversityHint,
            icon: Icons.account_balance_outlined,
            validator: _requiredValidator,
          ),
          const Sizer(height: 16),
          ProfileTextField(
            controller: studyFieldController,
            label: S.current.specialization,
            hint: S.current.specializationHint,
            icon: Icons.school_outlined,
            validator: _requiredValidator,
          ),
          const Sizer(height: 24),
          SectionLabel(
            title: S.current.chooseYourGender,
            subtitle: S.current.profileGenderReason,
          ),
          const Sizer(height: 10),
          GenderSelector(selected: state.gender),
          const Sizer(height: 24),
          SectionLabel(
            title: S.current.interests,
            subtitle: S.current.profileInterestsReason,
          ),
          const Sizer(height: 10),
          const InterestSuggestions(),
          if (state.interests.isNotEmpty) ...[
            const Sizer(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: state.interests
                  .map(
                    (interest) => InputChip(
                      label: Text(interest),
                      onDeleted: () => cubit.removeInterest(interest),
                      deleteIcon: const Icon(Icons.close, size: 16),
                      backgroundColor: ColorRes.anisTagGreen,
                      side: BorderSide.none,
                      labelStyle:
                          Theme.of(context).textTheme.bodySmall?.copyWith(
                                color: ColorRes.anisGreen,
                                fontWeight: FontWeight.w700,
                                overflow: TextOverflow.visible,
                              ),
                    ),
                  )
                  .toList(),
            ),
          ],
          const Sizer(height: 12),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: ProfileTextField(
                  controller: interestController,
                  label: S.current.addAnotherInterest,
                  hint: S.current.interestHint,
                  icon: Icons.add_circle_outline_rounded,
                  textInputAction: TextInputAction.done,
                  onSubmitted: (_) => _addCustomInterest(context),
                ),
              ),
              const Sizer(width: 8),
              Padding(
                padding: const EdgeInsets.only(top: 28),
                child: IconButton.filled(
                  tooltip: S.current.add,
                  onPressed: () => _addCustomInterest(context),
                  style: IconButton.styleFrom(
                    backgroundColor: ColorRes.anisGreen,
                    foregroundColor: ColorRes.white,
                  ),
                  icon: const Icon(Icons.add_rounded),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  static String? _requiredValidator(String? value) {
    return value == null || value.trim().isEmpty
        ? S.current.fieldRequired
        : null;
  }
}

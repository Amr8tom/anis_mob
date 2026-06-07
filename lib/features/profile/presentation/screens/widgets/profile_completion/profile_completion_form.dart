import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/profile_completion_cubit.dart';

class ProfileCompletionForm extends StatelessWidget {
  final ProfileCompletionState state;

  const ProfileCompletionForm({super.key, required this.state});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<ProfileCompletionCubit>();

    return Form(
      key: cubit.formKey,
      child: ListView(
        physics: const BouncingScrollPhysics(),
        padding: EdgeInsets.fromLTRB(
          AppSizes.padding,
          AppSizes.md,
          AppSizes.padding,
          AppSizes.xl,
        ),
        children: [
          _ProfileTextField(
            controller: cubit.emailController,
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
          _ProfileTextField(
            controller: cubit.universityController,
            label: S.current.university,
            hint: S.current.profileUniversityHint,
            icon: Icons.account_balance_outlined,
            validator: _requiredValidator,
          ),
          const Sizer(height: 16),
          _ProfileTextField(
            controller: cubit.studyFieldController,
            label: S.current.specialization,
            hint: S.current.specializationHint,
            icon: Icons.school_outlined,
            validator: _requiredValidator,
          ),
          const Sizer(height: 24),
          _SectionLabel(
            title: S.current.chooseYourGender,
            subtitle: S.current.profileGenderReason,
          ),
          const Sizer(height: 10),
          _GenderSelector(selected: state.gender),
          const Sizer(height: 24),
          _SectionLabel(
            title: S.current.interests,
            subtitle: S.current.profileInterestsReason,
          ),
          const Sizer(height: 10),
          const _InterestSuggestions(),
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
                child: _ProfileTextField(
                  controller: cubit.interestController,
                  label: S.current.addAnotherInterest,
                  hint: S.current.interestHint,
                  icon: Icons.add_circle_outline_rounded,
                  textInputAction: TextInputAction.done,
                  onSubmitted: (_) => cubit.addCustomInterest(),
                ),
              ),
              const Sizer(width: 8),
              Padding(
                padding: const EdgeInsets.only(top: 28),
                child: IconButton.filled(
                  tooltip: S.current.add,
                  onPressed: cubit.addCustomInterest,
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

class _SectionLabel extends StatelessWidget {
  final String title;
  final String subtitle;

  const _SectionLabel({required this.title, required this.subtitle});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: tt.titleSmall?.copyWith(
            color: ColorRes.anisNavy,
            fontWeight: FontWeight.w800,
          ),
        ),
        const Sizer(height: 3),
        Text(
          subtitle,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
        ),
      ],
    );
  }
}

class _GenderSelector extends StatelessWidget {
  final String selected;

  const _GenderSelector({required this.selected});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<ProfileCompletionCubit>();
    return Row(
      children: [
        Expanded(
          child: _GenderOption(
            label: S.current.male,
            icon: Icons.male_rounded,
            selected: selected == 'male',
            onTap: () => cubit.selectGender('male'),
          ),
        ),
        const Sizer(width: 10),
        Expanded(
          child: _GenderOption(
            label: S.current.female,
            icon: Icons.female_rounded,
            selected: selected == 'female',
            onTap: () => cubit.selectGender('female'),
          ),
        ),
      ],
    );
  }
}

class _GenderOption extends StatelessWidget {
  final String label;
  final IconData icon;
  final bool selected;
  final VoidCallback onTap;

  const _GenderOption({
    required this.label,
    required this.icon,
    required this.selected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        padding: EdgeInsets.symmetric(vertical: AppSizes.md),
        decoration: BoxDecoration(
          color: selected ? ColorRes.anisTagGreen : ColorRes.anisInputBg,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: selected ? ColorRes.anisGreen : ColorRes.anisInputBorder,
            width: selected ? 2 : 1,
          ),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: ColorRes.anisGreen),
            const Sizer(width: 6),
            Text(
              label,
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    color: ColorRes.anisNavy,
                    fontWeight: FontWeight.w700,
                  ),
            ),
          ],
        ),
      ),
    );
  }
}

class _InterestSuggestions extends StatelessWidget {
  const _InterestSuggestions();

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<ProfileCompletionCubit>();
    final suggestions = [
      S.current.interestProgramming,
      S.current.interestMathematics,
      S.current.interestLanguages,
      S.current.interestMedicine,
      S.current.interestEngineering,
    ];

    return Wrap(
      spacing: 8,
      runSpacing: 8,
      children: suggestions
          .map(
            (interest) => ActionChip(
              label: Text(interest),
              avatar: const Icon(Icons.add_rounded, size: 16),
              onPressed: () => cubit.addSuggestedInterest(interest),
              backgroundColor: ColorRes.anisInputBg,
              side: const BorderSide(color: ColorRes.anisInputBorder),
              labelStyle: Theme.of(context).textTheme.bodySmall?.copyWith(
                    color: ColorRes.anisTextDark,
                    fontWeight: FontWeight.w600,
                    overflow: TextOverflow.visible,
                  ),
            ),
          )
          .toList(),
    );
  }
}

class _ProfileTextField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final IconData icon;
  final TextInputType? keyboardType;
  final TextInputAction? textInputAction;
  final String? Function(String?)? validator;
  final ValueChanged<String>? onSubmitted;

  const _ProfileTextField({
    required this.controller,
    required this.label,
    required this.hint,
    required this.icon,
    this.keyboardType,
    this.textInputAction,
    this.validator,
    this.onSubmitted,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: tt.bodySmall?.copyWith(
            color: ColorRes.anisTextDark,
            fontWeight: FontWeight.w700,
            overflow: TextOverflow.visible,
          ),
        ),
        const Sizer(height: 7),
        TextFormField(
          controller: controller,
          keyboardType: keyboardType,
          textInputAction: textInputAction,
          validator: validator,
          onFieldSubmitted: onSubmitted,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextDark,
            overflow: TextOverflow.visible,
          ),
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: tt.bodyMedium?.copyWith(
              color: ColorRes.anisTextMuted,
              overflow: TextOverflow.visible,
            ),
            errorStyle: tt.bodySmall?.copyWith(
              color: ColorRes.anisErrorRed,
              overflow: TextOverflow.visible,
            ),
            prefixIcon: Icon(icon, color: ColorRes.anisGreen),
            filled: true,
            fillColor: ColorRes.anisInputBg,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisGreen, width: 2),
            ),
          ),
        ),
      ],
    );
  }
}

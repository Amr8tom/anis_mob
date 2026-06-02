import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/step_dark_text_field_widget.dart';
import 'shared/step_dropdown_field_widget.dart';
import 'shared/step_section_label_widget.dart';
import 'shared/step_year_picker_widget.dart';

/// Step 1 — University, study major, year of study.
/// All selections are forwarded to [UserInfoCubit] immediately.
/// This widget contains zero business logic.
class StepUniversityWidget extends StatelessWidget {
  const StepUniversityWidget({super.key});

  static const _universities = [
    'Cairo University',
    'Ain Shams University',
    'Alexandria University',
    'Helwan University',
    'Mansoura University',
    'Tanta University',
    'Assiut University',
    'Zagazig University',
    'Suez Canal University',
    'Benha University',
    'Menofia University',
    'South Valley University',
    'Beni-Suef University',
    'Fayoum University',
    'Kafrelsheikh University',
    'Damietta University',
    'Sohag University',
    'Luxor University',
    'Port Said University',
    'Arish University',
    'October 6 University',
    'Modern Sciences and Arts University (MSA)',
    'British University in Egypt (BUE)',
    'German University in Cairo (GUC)',
    'American University in Cairo (AUC)',
    'Misr International University (MIU)',
    'Future University in Egypt (FUE)',
    'Nile University',
    'Other',
  ];

  static const _years = [
    '1st Year', '2nd Year', '3rd Year',
    '4th Year', '5th Year', 'Graduate',
  ];

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) =>
          p.university != c.university ||
          p.studyMajor != c.studyMajor ||
          p.yearOfStudy != c.yearOfStudy,
      builder: (context, state) {
        final cubit = context.read<UserInfoCubit>();

        return SingleChildScrollView(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Sizer(height: AppSizes.sm),

              // ── University ────────────────────────────────────────────────
              StepSectionLabel(
                icon: Icons.account_balance_rounded,
                title: S.current.university,
              ),
              Sizer(height: AppSizes.sm),
              StepDropdownField<String>(
                value: state.university.isEmpty ? null : state.university,
                hint: S.current.selectUniversity,
                items: _universities,
                onChanged: (v) => cubit.setUniversity(v ?? ''),
                prefixIcon: Icons.school_outlined,
              ),

              Sizer(height: AppSizes.md),

              // ── Study major ───────────────────────────────────────────────
              StepSectionLabel(
                icon: Icons.auto_stories_rounded,
                title: S.current.studyMajor,
              ),
              Sizer(height: AppSizes.sm),
              StepDarkTextField(
                controller: cubit.majorController,
                hint: S.current.typeYourMajor,
                prefixIcon: Icons.book_outlined,
                onChanged: cubit.setStudyMajor,
              ),

              Sizer(height: AppSizes.md),

              // ── Year of study ─────────────────────────────────────────────
              StepSectionLabel(
                icon: Icons.calendar_today_rounded,
                title: S.current.yearOfStudy,
              ),
              Sizer(height: AppSizes.sm),
              StepYearPicker(
                selected: state.yearOfStudy,
                years: _years,
                onSelect: cubit.setYearOfStudy,
              ),

              Sizer(height: AppSizes.sm),
            ],
          ),
        );
      },
    );
  }
}

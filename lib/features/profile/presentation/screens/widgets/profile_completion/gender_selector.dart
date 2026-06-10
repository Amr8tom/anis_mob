import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/profile_completion_cubit.dart';
import 'gender_option.dart';

class GenderSelector extends StatelessWidget {
  final String selected;

  const GenderSelector({super.key, required this.selected});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<ProfileCompletionCubit>();
    return Row(
      children: [
        Expanded(
          child: GenderOption(
            label: S.current.male,
            icon: Icons.male_rounded,
            selected: selected == 'male',
            onTap: () => cubit.selectGender('male'),
          ),
        ),
        const Sizer(width: 10),
        Expanded(
          child: GenderOption(
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

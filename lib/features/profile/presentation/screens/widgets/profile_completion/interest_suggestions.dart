import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/profile_completion_cubit.dart';

class InterestSuggestions extends StatelessWidget {
  const InterestSuggestions({super.key});

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

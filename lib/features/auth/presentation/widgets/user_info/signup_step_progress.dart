import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';

class SignUpStepProgress extends StatelessWidget {
  const SignUpStepProgress({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) => p.step != c.step,
      builder: (_, state) {
        final step = state.step;
        final total = UserInfoCubit.totalSteps;
        final tt = Theme.of(context).textTheme;

        return Container(
          color: ColorRes.white,
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.md,
            AppSizes.padding,
            AppSizes.sm,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Progress bar
              ClipRRect(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
                child: LinearProgressIndicator(
                  value: (step + 1) / total,
                  minHeight: 4,
                  backgroundColor: ColorRes.anisLine,
                  valueColor: const AlwaysStoppedAnimation<Color>(
                    ColorRes.anisGreen,
                  ),
                ),
              ),
              const Sizer(height: 8),
              Text(
                S.current.stepIndicator(
                  step + 1,
                  total,
                  S.current.yourAccount,
                ),
                style: tt.labelSmall?.copyWith(
                  color: ColorRes.anisTextMuted,
                  letterSpacing: 0.2,
                  fontSize: 12,
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

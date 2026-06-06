import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/utils/enums/general_status.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'step_back_button_widget.dart';
import 'step_next_button_widget.dart';

class StepFooterWidget extends StatelessWidget {
  final int step;
  final int totalSteps;
  final VoidCallback onNext;
  final VoidCallback onBack;

  const StepFooterWidget({
    super.key,
    required this.step,
    required this.totalSteps,
    required this.onNext,
    required this.onBack,
  });

  bool get _isLast => step == totalSteps - 1;

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (prev, curr) => prev.status != curr.status,
      builder: (_, state) {
        final isLoading = state.status.isLoading;
        return Padding(
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.sm,
            AppSizes.padding,
            AppSizes.ld,
          ),
          child: Row(
            children: [
              if (step > 0) ...[
                const Sizer(width: 8),
                StepBackButton(onTap: isLoading ? null : onBack),
                const Sizer(width: 8),
              ],
              Expanded(
                child: StepNextButton(
                  isLast: _isLast,
                  isLoading: isLoading,
                  onTap: isLoading ? null : onNext,
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

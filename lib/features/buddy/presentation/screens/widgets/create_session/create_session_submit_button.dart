import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/action_guard.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';

/// Section 6 — submit button that reacts to [BuddyActionStatus.loading].
class CreateSessionSubmitButton extends StatelessWidget {
  const CreateSessionSubmitButton({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BuddyCubit, BuddyState>(
      buildWhen: (p, c) =>
          p.createStatus != c.createStatus || p.isGuest != c.isGuest,
      builder: (context, state) {
        final isLoading = state.createStatus == BuddyActionStatus.loading;
        return SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            onPressed: isLoading
                ? null
                : () => ActionGuard.run(
                      context,
                      isGuest: state.isGuest,
                      action: () {
                        final cubit = context.read<BuddyCubit>();
                        if (cubit.state.selectedWorkspace == null) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text(S.current.selectWorkspaceHint),
                              backgroundColor: ColorRes.anisErrorRed,
                              behavior: SnackBarBehavior.floating,
                            ),
                          );
                          return;
                        }
                        cubit.createSession();
                      },
                    ),
            style: ElevatedButton.styleFrom(
              backgroundColor: ColorRes.anisGreen,
              foregroundColor: ColorRes.white,
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              ),
              padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
            ),
            child: isLoading
                ? SizedBox(
                    width: AppSizes.iconMd,
                    height: AppSizes.iconMd,
                    child: const CircularProgressIndicator(
                      strokeWidth: 2,
                      color: ColorRes.white,
                    ),
                  )
                : Text(
                    S.current.createSessionSubmit,
                    style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.white,
                        ),
                  ),
          ),
        );
      },
    );
  }
}

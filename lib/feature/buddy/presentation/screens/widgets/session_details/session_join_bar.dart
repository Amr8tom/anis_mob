import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import '../../../controller/buddy_cubit.dart';

class SessionJoinBar extends StatelessWidget {
  final BuddySessionEntity session;
  final bool isFull;
  const SessionJoinBar({super.key, required this.session, required this.isFull});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md + MediaQuery.of(context).padding.bottom,
      ),
      decoration: BoxDecoration(
        color: ColorRes.white,
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.08),
            blurRadius: AppSizes.md,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: BlocBuilder<BuddyCubit, BuddyState>(
        builder: (context, state) {
          final isJoined = state.joinedSessionId == session.id;
          final isLoading = state.joinStatus == BuddyActionStatus.loading;

          if (isJoined) {
            return Container(
              padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 4),
              decoration: BoxDecoration(
                color: ColorRes.anisTagGreen,
                borderRadius:
                    BorderRadius.circular(AppSizes.borderRadiusXLg),
              ),
              alignment: Alignment.center,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.check_circle_rounded,
                      size: AppSizes.iconSm, color: ColorRes.anisGreen),
                  const Sizer(width: 8),
                  Text(
                    S.current.sessionJoined,
                    style: tt.bodyLarge?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: ColorRes.anisGreen,
                    ),
                  ),
                ],
              ),
            );
          }

          return SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: isFull || isLoading
                  ? null
                  : () =>
                      context.read<BuddyCubit>().joinSession(context, session),
              style: ElevatedButton.styleFrom(
                backgroundColor:
                    isFull ? ColorRes.anisChipBg : ColorRes.anisGreen,
                disabledBackgroundColor:
                    isFull ? ColorRes.anisChipBg : null,
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
              ),
              child: isLoading
                  ? SizedBox(
                      width: AppSizes.iconMd,
                      height: AppSizes.iconMd,
                      child: const CircularProgressIndicator(
                        strokeWidth: 2,
                        color: Colors.white,
                      ),
                    )
                  : Text(
                      isFull ? S.current.sessionFull : S.current.joinSession,
                      style: tt.bodyLarge?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: isFull
                            ? ColorRes.anisHintText
                            : ColorRes.white,
                      ),
                    ),
            ),
          );
        },
      ),
    );
  }
}

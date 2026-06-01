import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../domain/entity/buddy_session_entity.dart';
import '../../../controller/buddy_cubit.dart';

/// Tappable card showing the session's embedded workspace.
/// Tapping navigates to the full workspace details screen via [BuddyCubit].
class SessionWorkspaceCard extends StatelessWidget {
  final BuddySessionEntity session;
  const SessionWorkspaceCard({super.key, required this.session});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final workspace = session.workspace;

    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: GestureDetector(
        onTap: () =>
            context.read<BuddyCubit>().openWorkspaceDetails(context, session),
        child: Container(
          decoration: BoxDecoration(
            color: ColorRes.white,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            boxShadow: [
              BoxShadow(
                color: ColorRes.anisNavy.withOpacity(0.05),
                blurRadius: AppSizes.sm + 2,
                offset: const Offset(0, 2),
              ),
            ],
          ),
          padding: EdgeInsets.all(AppSizes.md),
          child: Row(
            children: [
              // Location icon
              Container(
                width: AppSizes.iconXLarge + 4,
                height: AppSizes.iconXLarge + 4,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      ColorRes.anisGreen.withOpacity(0.14),
                      ColorRes.anisGreen.withOpacity(0.06),
                    ],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusLg),
                ),
                child: Icon(
                  Icons.location_on_rounded,
                  size: AppSizes.iconMd,
                  color: ColorRes.anisGreen,
                ),
              ),
              const Sizer(width: 12),

              // Name + address from the embedded WorkspaceEntity
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      workspace.name,
                      textAlign: TextAlign.start,
                      style: tt.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: ColorRes.anisNavy,
                      ),
                    ),
                    const Sizer(height: 3),
                    Text(
                      workspace.address,
                      textAlign: TextAlign.start,
                      style: tt.bodySmall
                          ?.copyWith(color: ColorRes.anisTextMuted),
                    ),
                  ],
                ),
              ),

              // Arrow badge
              Container(
                padding: EdgeInsets.all(AppSizes.xs + 1),
                decoration: BoxDecoration(
                  color: ColorRes.anisTagGreen,
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
                child: Icon(
                  Icons.arrow_forward_ios_rounded,
                  size: AppSizes.iconXs,
                  color: ColorRes.anisGreen,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

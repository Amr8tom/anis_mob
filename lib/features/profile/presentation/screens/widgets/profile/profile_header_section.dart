import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../controller/profile_cubit.dart';

import 'subscription_badge.dart';

class ProfileHeaderSection extends StatelessWidget {
  const ProfileHeaderSection({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProfileCubit, ProfileState>(
      builder: (context, state) {
        final profile = state.profile;
        final avatarPath = state.avatarPath ?? profile?.avatarPath;
        final name = profile?.name ?? '████ █████ ████████';
        final uni = profile?.university ?? '████████████';
        final initials = profile?.initials ?? 'م.أ';
        final subType = profile?.subscriptionType ?? 'gold';

        return Container(
          width: double.infinity,
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding,
            AppSizes.md,
            AppSizes.padding,
            AppSizes.xl,
          ),
          decoration: BoxDecoration(
            color: ColorRes.anisGreen,
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(AppSizes.borderRadiusXXLg),
              bottomRight: Radius.circular(AppSizes.borderRadiusXXLg),
            ),
          ),
          child: Column(
            children: [
              // ── Avatar with tap-to-upload ────────────────────
              GestureDetector(
                onTap: () => context.read<ProfileCubit>().pickAvatar(),
                child: Stack(
                  children: [
                    Container(
                      width: AppSizes.iconXLarge * 1.5,
                      height: AppSizes.iconXLarge * 1.5,
                      decoration: BoxDecoration(
                        color: ColorRes.anisAvatarDark,
                        shape: BoxShape.circle,
                        border: Border.all(
                          color: ColorRes.white.withValues(alpha: 0.3),
                          width: 2.5,
                        ),
                        image: avatarPath != null
                            ? DecorationImage(
                                image: FileImage(File(avatarPath)),
                                fit: BoxFit.cover,
                              )
                            : null,
                      ),
                      alignment: Alignment.center,
                      child: avatarPath == null
                          ? Text(
                              initials,
                              style: Theme.of(context)
                                  .textTheme
                                  .headlineSmall
                                  ?.copyWith(
                                    color: ColorRes.white,
                                    fontWeight: FontWeight.w800,
                                  ),
                            )
                          : null,
                    ),
                    // Camera badge
                    Positioned(
                      bottom: 0,
                      right: 0,
                      child: Container(
                        width: AppSizes.iconMd,
                        height: AppSizes.iconMd,
                        decoration: BoxDecoration(
                          color: ColorRes.white,
                          shape: BoxShape.circle,
                          border: Border.all(
                            color: ColorRes.anisGreen,
                            width: 1.5,
                          ),
                        ),
                        child: Icon(
                          Icons.camera_alt_rounded,
                          size: AppSizes.iconXs,
                          color: ColorRes.anisGreen,
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              const Sizer(height: 12),

              // Name
              Text(
                name,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.w800,
                      color: ColorRes.white,
                    ),
              ),
              const Sizer(height: 4),

              // University
              Text(
                uni,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.75),
                    ),
              ),
              const Sizer(height: 12),

              // Subscription badge
              SubscriptionBadge(type: subType),
            ],
          ),
        );
      },
    );
  }
}

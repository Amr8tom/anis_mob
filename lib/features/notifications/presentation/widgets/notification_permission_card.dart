import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controller/notifications_cubit.dart';

class NotificationPermissionCard extends StatelessWidget {
  const NotificationPermissionCard({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<NotificationsCubit, NotificationsState>(
      builder: (context, state) {
        final enabled = state.permissionGranted == true;
        final isSyncing = state.isSyncing;
        final textTheme = Theme.of(context).textTheme;

        return Container(
          margin: EdgeInsets.only(bottom: AppSizes.md),
          padding: EdgeInsets.all(AppSizes.md),
          decoration: BoxDecoration(
            color: enabled ? ColorRes.anisMintBg : ColorRes.anisWarningBg,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            border: Border.all(
              color: enabled ? ColorRes.anisLine : ColorRes.anisGold,
            ),
          ),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: 46,
                height: 46,
                decoration: BoxDecoration(
                  color: ColorRes.anisButtonGreen.withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
                child: Icon(
                  enabled
                      ? Icons.notifications_active_rounded
                      : Icons.notifications_none_rounded,
                  color: ColorRes.anisGreen,
                ),
              ),
              const Sizer(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      enabled
                          ? S.current.notificationPermissionEnabledTitle
                          : S.current.notificationPermissionTitle,
                      style: textTheme.bodyLarge?.copyWith(
                        color: ColorRes.anisTextDark,
                        fontWeight: FontWeight.w900,
                      ),
                    ),
                    const Sizer(height: 6),
                    Text(
                      enabled
                          ? S.current.notificationPermissionEnabledBody
                          : S.current.notificationPermissionBody,
                      style: textTheme.bodySmall?.copyWith(
                        color: ColorRes.anisTextMuted,
                        height: 1.45,
                      ),
                    ),
                    if (!enabled) ...[
                      const Sizer(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton.icon(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: ColorRes.anisButtonGreen,
                            foregroundColor: ColorRes.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(
                                AppSizes.borderRadiusMd,
                              ),
                            ),
                          ),
                          onPressed: isSyncing
                              ? null
                              : context.read<NotificationsCubit>().syncToken,
                          icon: isSyncing
                              ? const SizedBox(
                                  width: 16,
                                  height: 16,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2,
                                    color: ColorRes.white,
                                  ),
                                )
                              : const Icon(Icons.notifications_active_rounded),
                          label: Text(
                            isSyncing
                                ? S.current.loading
                                : S.current.notificationPermissionButton,
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

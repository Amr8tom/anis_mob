import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/custom_ui.dart';
import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/service_locator/service_locator.dart';
import '../../../../generated/l10n.dart';
import '../controller/notification_preferences_cubit.dart';
import '../controller/notifications_cubit.dart';
import '../widgets/notification_permission_card.dart';
import '../widgets/notification_preference_tile.dart';

class NotificationsScreen extends StatelessWidget {
  const NotificationsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => serviceLocator<NotificationPreferencesCubit>()..load(),
      child: const _NotificationsView(),
    );
  }
}

class _NotificationsView extends StatefulWidget {
  const _NotificationsView();

  @override
  State<_NotificationsView> createState() => _NotificationsViewState();
}

class _NotificationsViewState extends State<_NotificationsView> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!mounted) return;
      context.read<NotificationsCubit>().refreshPermissionStatus();
    });
  }

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<NotificationPreferencesCubit,
        NotificationPreferencesState>(
      listener: (context, state) {
        if (state.status.isSaved) {
          CustomUI.snackBarSuccess(
            context: context,
            message: S.current.notificationPreferencesSaved,
          );
        }

        if (state.status.isError) {
          CustomUI.snackBarFailure(
            context: context,
            message: state.errorMessage ?? S.current.generalError,
          );
        }
      },
      builder: (context, state) {
        final cubit = context.read<NotificationPreferencesCubit>();
        final preferences = state.preferences;

        return Scaffold(
          backgroundColor: ColorRes.white,
          appBar: DAppBar(
            title: S.current.notificationPreferencesTitle,
            showBackArrow: true,
          ),
          body: RefreshIndicator(
            color: ColorRes.anisGreen,
            onRefresh: cubit.load,
            child: ListView(
              padding: EdgeInsets.all(AppSizes.padding),
              children: [
                Text(
                  S.current.notificationPreferencesSubtitle,
                  style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: ColorRes.anisTextMuted,
                        height: 1.5,
                      ),
                ),
                const Sizer(height: 18),
                const NotificationPermissionCard(),
                if (state.status.isLoading)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.all(32),
                      child: CircularProgressIndicator(
                        color: ColorRes.anisGreen,
                      ),
                    ),
                  )
                else ...[
                  NotificationPreferenceTile(
                    icon: Icons.event_available_rounded,
                    title: S.current.notificationSessionReminders,
                    subtitle: S.current.notificationSessionRemindersDesc,
                    value: preferences.sessionReminders,
                    isSaving: state.status.isSaving,
                    onChanged: cubit.setSessionReminders,
                  ),
                  NotificationPreferenceTile(
                    icon: Icons.workspace_premium_rounded,
                    title: S.current.notificationSubscriptionAlerts,
                    subtitle: S.current.notificationSubscriptionAlertsDesc,
                    value: preferences.subscriptionAlerts,
                    isSaving: state.status.isSaving,
                    onChanged: cubit.setSubscriptionAlerts,
                  ),
                  NotificationPreferenceTile(
                    icon: Icons.local_offer_rounded,
                    title: S.current.notificationOffersMarketing,
                    subtitle: S.current.notificationOffersMarketingDesc,
                    value: preferences.offersMarketing,
                    isSaving: state.status.isSaving,
                    onChanged: cubit.setOffersMarketing,
                  ),
                  NotificationPreferenceTile(
                    icon: Icons.storefront_rounded,
                    title: S.current.notificationWorkspaceUpdates,
                    subtitle: S.current.notificationWorkspaceUpdatesDesc,
                    value: preferences.workspaceUpdates,
                    isSaving: state.status.isSaving,
                    onChanged: cubit.setWorkspaceUpdates,
                  ),
                ],
              ],
            ),
          ),
        );
      },
    );
  }
}

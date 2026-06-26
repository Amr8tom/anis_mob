import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/utils/usecases/base_usecase.dart';
import '../../domain/entities/notification_preferences_entity.dart';
import '../../domain/use_cases/get_notification_preferences_use_case.dart';
import '../../domain/use_cases/update_notification_preferences_use_case.dart';

part 'notification_preferences_state.dart';

class NotificationPreferencesCubit extends Cubit<NotificationPreferencesState> {
  final GetNotificationPreferencesUseCase _getPreferences;
  final UpdateNotificationPreferencesUseCase _updatePreferences;

  NotificationPreferencesCubit(
    this._getPreferences,
    this._updatePreferences,
  ) : super(const NotificationPreferencesState());

  Future<void> load() async {
    emit(state.copyWith(status: NotificationPreferencesStatus.loading));

    final result = await _getPreferences(params: NoParams());
    result.fold(
      (failure) => emit(state.copyWith(
        status: NotificationPreferencesStatus.error,
        errorMessage: failure.message,
      )),
      (preferences) => emit(state.copyWith(
        status: NotificationPreferencesStatus.loaded,
        preferences: preferences,
      )),
    );
  }

  Future<void> setSessionReminders(bool value) {
    return _save(state.preferences.copyWith(sessionReminders: value));
  }

  Future<void> setSubscriptionAlerts(bool value) {
    return _save(state.preferences.copyWith(subscriptionAlerts: value));
  }

  Future<void> setOffersMarketing(bool value) {
    return _save(state.preferences.copyWith(offersMarketing: value));
  }

  Future<void> setWorkspaceUpdates(bool value) {
    return _save(state.preferences.copyWith(workspaceUpdates: value));
  }

  Future<void> _save(NotificationPreferencesEntity preferences) async {
    final previous = state.preferences;

    emit(state.copyWith(
      status: NotificationPreferencesStatus.saving,
      preferences: preferences,
      errorMessage: null,
    ));

    final result = await _updatePreferences(
      params: UpdateNotificationPreferencesParams(preferences: preferences),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        status: NotificationPreferencesStatus.error,
        preferences: previous,
        errorMessage: failure.message,
      )),
      (saved) => emit(state.copyWith(
        status: NotificationPreferencesStatus.saved,
        preferences: saved,
        errorMessage: null,
      )),
    );
  }
}

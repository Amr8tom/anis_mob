import 'dart:async';

import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/utils/usecases/base_usecase.dart';
import '../../domain/entity/study_session_entity.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../../domain/entity/workspace_attendance_entity.dart';
import '../../domain/use_cases/check_in_workspace_use_case.dart';
import '../../domain/use_cases/check_out_workspace_use_case.dart';
import '../../domain/use_cases/get_active_visit_use_case.dart';
import '../../domain/use_cases/get_today_sessions_use_case.dart';
import '../../domain/use_cases/get_user_profile_use_case.dart';
import '../../domain/use_cases/request_checkout_use_case.dart';

import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../../../../generated/l10n.dart';

part 'home_state.dart';

class HomeCubit extends Cubit<HomeState> {
  final GetUserProfileUseCase getUserProfileUseCase;
  final GetTodaySessionsUseCase getTodaySessionsUseCase;
  final CheckInWorkspaceUseCase checkInWorkspaceUseCase;
  final CheckOutWorkspaceUseCase checkOutWorkspaceUseCase;
  final RequestCheckoutUseCase requestCheckoutUseCase;
  final GetActiveVisitUseCase getActiveVisitUseCase;
  final LocalStorage localStorage;

  /// Polls the active visit while a checkout request is pending owner approval.
  Timer? _approvalPoll;

  HomeCubit({
    required this.getUserProfileUseCase,
    required this.getTodaySessionsUseCase,
    required this.checkInWorkspaceUseCase,
    required this.checkOutWorkspaceUseCase,
    required this.requestCheckoutUseCase,
    required this.getActiveVisitUseCase,
    required this.localStorage,
  }) : super(const HomeState()) {
    _initLocation();
    loadHomeData();
  }

  @override
  Future<void> close() {
    _approvalPoll?.cancel();
    return super.close();
  }

  void _initLocation() {
    final lat = localStorage.getDouble(key: StorageKeys.latitude.name, defaultValue: -999.0);
    final lng = localStorage.getDouble(key: StorageKeys.longitude.name, defaultValue: -999.0);
    final address = localStorage.getString(key: StorageKeys.address.name);

    if (lat != -999.0 && lng != -999.0 && address != null) {
      emit(state.copyWith(
        latitude: lat,
        longitude: lng,
        locationName: address,
      ));
    }
  }

  Future<void> updateUserLocation(double lat, double lng, String name) async {
    await localStorage.cacheDouble(key: StorageKeys.latitude.name, value: lat);
    await localStorage.cacheDouble(key: StorageKeys.longitude.name, value: lng);
    await localStorage.cacheString(key: StorageKeys.address.name, value: name);

    emit(state.copyWith(
      latitude: lat,
      longitude: lng,
      locationName: name,
    ));
  }

  // Home data

  Future<void> loadHomeData() async {
    emit(state.copyWith(status: HomeStatus.loading));

    final results = await Future.wait([
      getUserProfileUseCase(params: NoParams()),
      getTodaySessionsUseCase(params: NoParams()),
    ]);

    UserProfileEntity? profile;
    List<StudySessionEntity> sessions = [];
    String? error;

    results[0].fold(
      (failure) => error = failure.message,
      (data) => profile = data as UserProfileEntity,
    );
    results[1].fold(
      (failure) => error ??= failure.message,
      (data) => sessions = data as List<StudySessionEntity>,
    );

    if (profile != null) {
      emit(state.copyWith(
        status: HomeStatus.success,
        userProfile: profile,
        todaySessions: sessions,
        errorMessage: null,
      ));
    } else {
      emit(state.copyWith(
        status: HomeStatus.failure,
        errorMessage: error ?? S.current.generalError,
      ));
    }
  }

  Future<void> refresh() => loadHomeData();

  // Workspace check-in

  /// Called by [QrScannerScreen] after a QR code is successfully decoded.
  Future<void> checkIn(String qrPayload) async {
    if (state.attendanceStatus.isBusy) return;

    emit(state.copyWith(
      attendanceStatus: AttendanceStatus.checkingIn,
      clearAttendanceError: true,
    ));
    final checkInTime = DateTime.now();

    final result = await checkInWorkspaceUseCase.call(
      params: CheckInWorkspaceParams(
        qrPayload: qrPayload,
        checkInTime: checkInTime,
      ),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        attendanceStatus: AttendanceStatus.idle,
        attendanceError: failure.message,
      )),
      (session) => emit(state.copyWith(
        attendanceStatus: AttendanceStatus.checkedIn,
        activeSession: session,
        clearAttendanceError: true,
      )),
    );
  }

  // Workspace check-out / leave

  /// Single entry point for the "leave" button. Direct-checkout visits check out
  /// immediately; approval-mode paid visits send a request for the owner instead.
  Future<void> leaveWorkspace() async {
    final session = state.activeSession;
    if (session == null || state.attendanceStatus.isBusy) return;

    if (session.canCheckOutDirectly) {
      await _checkOutDirect(session);
    } else {
      await _requestCheckout(session);
    }
  }

  Future<void> _checkOutDirect(WorkspaceAttendanceEntity session) async {
    emit(state.copyWith(
      attendanceStatus: AttendanceStatus.checkingOut,
      clearAttendanceError: true,
    ));

    final result = await checkOutWorkspaceUseCase.call(
      params: CheckOutWorkspaceParams(
        attendanceId: session.attendanceId,
        workspaceId: session.workspaceId,
        workspaceName: session.workspaceName,
        checkInTime: session.checkInTime,
        elapsedTime: session.elapsed,
      ),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        // Keep checkedIn so user can retry
        attendanceStatus: AttendanceStatus.checkedIn,
        attendanceError: failure.message,
      )),
      (completed) => emit(state.copyWith(
        attendanceStatus: AttendanceStatus.idle,
        clearSession: true,
        clearAttendanceError: true,
      )),
    );
  }

  Future<void> _requestCheckout(WorkspaceAttendanceEntity session) async {
    emit(state.copyWith(
      attendanceStatus: AttendanceStatus.requestingCheckout,
      clearAttendanceError: true,
    ));

    final result = await requestCheckoutUseCase.call(
      params: RequestCheckoutParams(attendanceId: session.attendanceId),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        attendanceStatus: AttendanceStatus.checkedIn,
        attendanceError: failure.message,
      )),
      (updated) {
        if (!updated.isActive) {
          // Edge case: server checked the user out directly (e.g. mode changed).
          emit(state.copyWith(
            attendanceStatus: AttendanceStatus.idle,
            clearSession: true,
            clearAttendanceError: true,
          ));
          return;
        }
        emit(state.copyWith(
          attendanceStatus: AttendanceStatus.checkoutPending,
          activeSession: updated,
          clearAttendanceError: true,
        ));
        _startApprovalPolling();
      },
    );
  }

  /// While a request is pending, poll the active visit. When the owner approves,
  /// the active visit disappears (null) and we transition to idle.
  void _startApprovalPolling() {
    _approvalPoll?.cancel();
    _approvalPoll = Timer.periodic(const Duration(seconds: 20), (_) async {
      if (!state.attendanceStatus.isCheckoutPending) {
        _approvalPoll?.cancel();
        return;
      }

      final result = await getActiveVisitUseCase.call();
      result.fold(
        (_) {}, // transient network error — keep polling
        (active) {
          if (active == null || !active.isActive) {
            // Owner approved → the visit is closed.
            _approvalPoll?.cancel();
            emit(state.copyWith(
              attendanceStatus: AttendanceStatus.idle,
              clearSession: true,
              clearAttendanceError: true,
            ));
          } else {
            // Still pending — refresh the snapshot (timer, etc.).
            emit(state.copyWith(activeSession: active));
          }
        },
      );
    });
  }

  void clearAttendanceError() =>
      emit(state.copyWith(clearAttendanceError: true));
}

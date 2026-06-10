import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/utils/usecases/base_usecase.dart';
import '../../domain/entity/study_session_entity.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../../domain/entity/workspace_attendance_entity.dart';
import '../../domain/use_cases/check_in_workspace_use_case.dart';
import '../../domain/use_cases/check_out_workspace_use_case.dart';
import '../../domain/use_cases/get_today_sessions_use_case.dart';
import '../../domain/use_cases/get_user_profile_use_case.dart';

import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';

part 'home_state.dart';

class HomeCubit extends Cubit<HomeState> {
  final GetUserProfileUseCase getUserProfileUseCase;
  final GetTodaySessionsUseCase getTodaySessionsUseCase;
  final CheckInWorkspaceUseCase checkInWorkspaceUseCase;
  final CheckOutWorkspaceUseCase checkOutWorkspaceUseCase;
  final LocalStorage localStorage;

  HomeCubit({
    required this.getUserProfileUseCase,
    required this.getTodaySessionsUseCase,
    required this.checkInWorkspaceUseCase,
    required this.checkOutWorkspaceUseCase,
    required this.localStorage,
  }) : super(const HomeState()) {
    _initLocation();
    loadHomeData();
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
        errorMessage: error ?? 'حدث خطأ ما',
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

  // Workspace check-out

  Future<void> checkOut() async {
    final session = state.activeSession;
    if (session == null || state.attendanceStatus.isBusy) return;

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

  void clearAttendanceError() =>
      emit(state.copyWith(clearAttendanceError: true));
}

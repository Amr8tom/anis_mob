part of 'home_cubit.dart';

enum HomeStatus { initial, loading, success, failure }

enum AttendanceStatus {
  idle, // not checked in
  checkingIn, // scanning / API call in progress
  checkedIn, // inside workspace, timer running
  requestingCheckout, // sending a "request to leave" to the owner
  checkoutPending, // request sent, awaiting owner approval
  checkingOut, // API call in progress
  checkedOut, // just finished before returning to idle
}

extension AttendanceStatusX on AttendanceStatus {
  bool get isIdle => this == AttendanceStatus.idle;
  bool get isCheckingIn => this == AttendanceStatus.checkingIn;
  bool get isCheckedIn => this == AttendanceStatus.checkedIn;
  bool get isRequestingCheckout => this == AttendanceStatus.requestingCheckout;
  bool get isCheckoutPending => this == AttendanceStatus.checkoutPending;
  bool get isCheckingOut => this == AttendanceStatus.checkingOut;
  bool get isCheckedOut => this == AttendanceStatus.checkedOut;
  bool get isBusy => isCheckingIn || isCheckingOut || isRequestingCheckout;
}

final class HomeState extends Equatable {
  final HomeStatus status;
  final UserProfileEntity? userProfile;
  final List<StudySessionEntity> todaySessions;
  final String? errorMessage;

  // Workspace attendance
  final AttendanceStatus attendanceStatus;
  final WorkspaceAttendanceEntity? activeSession;
  final String? attendanceError;

  // Location Selector
  final String? locationName;
  final double? latitude;
  final double? longitude;

  const HomeState({
    this.status = HomeStatus.initial,
    this.userProfile,
    this.todaySessions = const [],
    this.errorMessage,
    this.attendanceStatus = AttendanceStatus.idle,
    this.activeSession,
    this.attendanceError,
    this.locationName,
    this.latitude,
    this.longitude,
  });

  bool get isCheckedIn => activeSession != null && activeSession!.isActive;

  HomeState copyWith({
    HomeStatus? status,
    UserProfileEntity? userProfile,
    List<StudySessionEntity>? todaySessions,
    String? errorMessage,
    AttendanceStatus? attendanceStatus,
    WorkspaceAttendanceEntity? activeSession,
    bool clearSession = false,
    String? attendanceError,
    bool clearAttendanceError = false,
    String? locationName,
    double? latitude,
    double? longitude,
  }) {
    return HomeState(
      status: status ?? this.status,
      userProfile: userProfile ?? this.userProfile,
      todaySessions: todaySessions ?? this.todaySessions,
      errorMessage: errorMessage ?? this.errorMessage,
      attendanceStatus: attendanceStatus ?? this.attendanceStatus,
      activeSession:
          clearSession ? null : (activeSession ?? this.activeSession),
      attendanceError: clearAttendanceError
          ? null
          : (attendanceError ?? this.attendanceError),
      locationName: locationName ?? this.locationName,
      latitude: latitude ?? this.latitude,
      longitude: longitude ?? this.longitude,
    );
  }

  @override
  List<Object?> get props => [
        status,
        userProfile,
        todaySessions,
        errorMessage,
        attendanceStatus,
        activeSession,
        attendanceError,
        locationName,
        latitude,
        longitude,
      ];
}

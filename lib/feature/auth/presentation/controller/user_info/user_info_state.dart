part of 'user_info_cubit.dart';

@immutable
final class UserInfoState extends Equatable {
  final GeneralStatus status;
  // ── Step 0 — Name ──────────────────────────────────────
  final String name;
  // ── Step 1 — Academic info ─────────────────────────────
  final String university;
  final String studyMajor;
  final String yearOfStudy;
  // ── Step 2 — Gender & Avatar ───────────────────────────
  final int? genderId;    // 1 = male, 2 = female
  final String avatar;    // emoji
  // ── Step 3 — Email & Password ─────────────────────────
  final String email;
  // ── Wizard state ──────────────────────────────────────
  final int step;
  final String stepError;
  final String errorMessage;

  const UserInfoState({
    this.status = GeneralStatus.initialized,
    this.name = '',
    this.university = '',
    this.studyMajor = '',
    this.yearOfStudy = '',
    this.genderId,
    this.avatar = '',
    this.email = '',
    this.step = 0,
    this.stepError = '',
    this.errorMessage = '',
  });

  UserInfoState copyWith({
    GeneralStatus? status,
    String? name,
    String? university,
    String? studyMajor,
    String? yearOfStudy,
    int? genderId,
    String? avatar,
    String? email,
    int? step,
    String? stepError,
    String? errorMessage,
  }) {
    return UserInfoState(
      status: status ?? this.status,
      name: name ?? this.name,
      university: university ?? this.university,
      studyMajor: studyMajor ?? this.studyMajor,
      yearOfStudy: yearOfStudy ?? this.yearOfStudy,
      genderId: genderId ?? this.genderId,
      avatar: avatar ?? this.avatar,
      email: email ?? this.email,
      step: step ?? this.step,
      stepError: stepError ?? this.stepError,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [
    status, name, university, studyMajor, yearOfStudy,
    genderId, avatar, email, step, stepError, errorMessage,
  ];
}

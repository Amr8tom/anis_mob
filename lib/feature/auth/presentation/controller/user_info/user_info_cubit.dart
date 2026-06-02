import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:meta/meta.dart';

import '../../../../../core/local_storage/cache_helper.dart';
import '../../../../../core/local_storage/cache_keys.dart';
import '../../../../../core/utils/enums/general_status.dart';
import '../../../domain/use_cases/create_user_use_case.dart';
import '../../../../../generated/l10n.dart';

part 'user_info_state.dart';

class UserInfoCubit extends Cubit<UserInfoState> {
  final CreateUserUseCase _createUserUseCase;

  static const int totalSteps = 4;

  /// PageController lives here so every step screen stays stateless.
  final PageController pageController = PageController();

  /// Password + confirm controllers — owned by cubit, disposed in close().
  final TextEditingController passwordController    = TextEditingController();
  final TextEditingController confirmPasswordController = TextEditingController();
  final TextEditingController majorController        = TextEditingController();

  UserInfoCubit(this._createUserUseCase) : super(const UserInfoState());

  // ── Step navigation ────────────────────────────────────────────────────────

  void nextStep() {
    String? error;

    switch (state.step) {
      case 0:
        error = validateName(state.name);
      case 1:
        error = validateAcademicInfo();
      case 2:
        error = validateGenderAndAvatar();
      case 3:
        error = validateEmailAndPassword();
        if (error == null) {
          _submitRegistration(
            password: passwordController.text.trim(),
            email: state.email,
          );
          return;
        }
    }

    if (error != null) {
      emit(state.copyWith(stepError: error));
      return;
    }

    final next = state.step + 1;
    emit(state.copyWith(step: next, stepError: ''));
    pageController.animateToPage(
      next,
      duration: const Duration(milliseconds: 380),
      curve: Curves.easeInOutCubic,
    );
  }

  void backStep() {
    if (state.step == 0) return;
    final prev = state.step - 1;
    emit(state.copyWith(step: prev, stepError: ''));
    pageController.animateToPage(
      prev,
      duration: const Duration(milliseconds: 380),
      curve: Curves.easeInOutCubic,
    );
  }

  void clearStepError() => emit(state.copyWith(stepError: ''));
  void resetStatus() => emit(state.copyWith(status: GeneralStatus.initialized, errorMessage: ''));

  // ── Setters ────────────────────────────────────────────────────────────────

  void setName(String name) => emit(state.copyWith(name: name));
  void setUniversity(String university) => emit(state.copyWith(university: university));
  void setStudyMajor(String major) => emit(state.copyWith(studyMajor: major));
  void setYearOfStudy(String year) => emit(state.copyWith(yearOfStudy: year));
  void selectGender(int id) => emit(state.copyWith(genderId: id));
  void selectAvatar(String avatar) => emit(state.copyWith(avatar: avatar));
  void setEmail(String email) => emit(state.copyWith(email: email));

  // ── Validators ─────────────────────────────────────────────────────────────

  String? validateName(String name) {
    if (name.trim().length < 2) return S.current.nameTooShortError;
    return null;
  }

  String? validateAcademicInfo() {
    if (state.university.isEmpty) return S.current.selectUniversityError;
    if (majorController.text.trim().isEmpty) return S.current.selectMajorError;
    if (state.yearOfStudy.isEmpty) return S.current.selectYearError;
    return null;
  }

  String? validateGenderAndAvatar() {
    if (state.genderId == null) return S.current.selectGenderError;
    if (state.avatar.isEmpty) return S.current.selectAvatarError;
    return null;
  }

  String? validateEmailAndPassword() {
    final email = state.email.trim();
    if (email.isEmpty || !email.contains('@')) return S.current.invalidEmail;
    final pw = passwordController.text.trim();
    if (pw.isEmpty) return S.current.passwordEmptyError;
    if (pw.length < 6) return S.current.passwordTooShortError;
    if (!pw.contains(RegExp(r'[A-Z]'))) return S.current.passwordMissingUppercaseError;
    if (!pw.contains(RegExp(r'[a-z]'))) return S.current.passwordMissingLowercaseError;
    if (!pw.contains(RegExp(r'[0-9]'))) return S.current.passwordMissingNumberError;
    if (pw != confirmPasswordController.text.trim()) return S.current.passwordsDoNotMatch;
    return null;
  }

  // ── Submit ─────────────────────────────────────────────────────────────────

  Future<void> _submitRegistration({required String email, required String password}) async {
    emit(state.copyWith(status: GeneralStatus.loading));

    // Sync user selections to local cache
    await Future.wait([
      CacheHelper.putString(key: CacheKeys.userName,       value: state.name),
      CacheHelper.putString(key: CacheKeys.userEmail,      value: email),
      CacheHelper.putString(key: CacheKeys.userGender,     value: state.genderId.toString()),
      CacheHelper.putString(key: CacheKeys.userAvatar,     value: state.avatar),
      CacheHelper.putString(key: CacheKeys.userUniversity, value: state.university),
      CacheHelper.putString(key: CacheKeys.userMajor,      value: majorController.text.trim()),
      CacheHelper.putString(key: CacheKeys.userYear,       value: state.yearOfStudy),
    ]);

    final result = await _createUserUseCase.call(
      params: CreateUserInfoParams(
        fullName:   state.name,
        email:      email,
        password:   password,
        roleId:     1,
        genderId:   state.genderId,
        university: state.university,
        studyMajor: majorController.text.trim(),
        yearOfStudy: state.yearOfStudy,
      ),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        status: GeneralStatus.error,
        errorMessage: failure.message ?? S.current.generalError,
      )),
      (_) => emit(state.copyWith(status: GeneralStatus.success)),
    );
  }

  @override
  Future<void> close() {
    pageController.dispose();
    passwordController.dispose();
    confirmPasswordController.dispose();
    majorController.dispose();
    return super.close();
  }
}

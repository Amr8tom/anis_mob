import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../core/utils/enums/general_status.dart';
import '../../../domain/use_cases/cache_user_info_draft_use_case.dart';
import '../../../domain/use_cases/create_user_use_case.dart';
import '../../../../../generated/l10n.dart';

part 'user_info_state.dart';

class UserInfoCubit extends Cubit<UserInfoState> {
  final CreateUserUseCase _createUserUseCase;
  final CacheUserInfoDraftUseCase _cacheUserInfoDraftUseCase;

  static const int totalSteps = 2;

  /// PageController lives here so every step screen stays stateless.
  final PageController pageController = PageController();

  /// Password controllers — owned by cubit, disposed in close().
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController confirmPasswordController =
      TextEditingController();

  UserInfoCubit(
    this._createUserUseCase,
    this._cacheUserInfoDraftUseCase,
  ) : super(const UserInfoState());

  // ── Step navigation ────────────────────────────────────────────────────────

  void nextStep() {
    String? error;

    switch (state.step) {
      case 0:
        error = _validateAccountStep();
        if (error != null) break;
      case 1:
        error = _validateProfileStep();
        if (error == null) {
          _submitRegistration();
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
  void resetStatus() =>
      emit(state.copyWith(status: GeneralStatus.initialized, errorMessage: ''));

  // ── Setters ────────────────────────────────────────────────────────────────

  void setName(String v) => emit(state.copyWith(name: v));
  void setEmail(String v) => emit(state.copyWith(email: v));
  void setPhone(String v) => emit(state.copyWith(phone: v));
  void setWhatsAppNumber(String v) => emit(state.copyWith(whatsAppNumber: v));
  void selectGender(int id) => emit(state.copyWith(genderId: id));
  void setSpecialization(String v) => emit(state.copyWith(specialization: v));

  // ── Validators ─────────────────────────────────────────────────────────────

  String? _validateAccountStep() {
    if (state.name.trim().length < 2) return S.current.nameTooShortError;
    final email = state.email.trim();
    if (email.isEmpty || !email.contains('@')) return S.current.invalidEmail;
    if (state.phone.trim().length < 7) return S.current.phoneRequired;
    if (state.whatsAppNumber.trim().length < 7) {
      return S.current.whatsAppNumberRequired;
    }
    final pw = passwordController.text.trim();
    if (pw.isEmpty) return S.current.passwordEmptyError;
    if (pw.length < 6) return S.current.passwordTooShortError;
    if (!pw.contains(RegExp(r'[A-Z]'))) {
      return S.current.passwordMissingUppercaseError;
    }
    if (!pw.contains(RegExp(r'[a-z]'))) {
      return S.current.passwordMissingLowercaseError;
    }
    if (!pw.contains(RegExp(r'[0-9]'))) {
      return S.current.passwordMissingNumberError;
    }
    if (pw != confirmPasswordController.text.trim()) {
      return S.current.passwordsDoNotMatch;
    }
    return null;
  }

  String? _validateProfileStep() {
    if (state.genderId == null) return S.current.selectGenderError;
    if (state.specialization.trim().isEmpty) return S.current.fieldRequired;
    return null;
  }

  // ── Submit ─────────────────────────────────────────────────────────────────

  Future<void> _submitRegistration() async {
    emit(state.copyWith(status: GeneralStatus.loading));

    await _cacheUserInfoDraftUseCase(
      CacheUserInfoDraftParams(
        name: state.name,
        email: state.email,
        whatsAppNumber: state.whatsAppNumber,
        gender: state.genderId.toString(),
        avatar: '',
        university: '',
        major: state.specialization,
        year: '',
      ),
    );

    final result = await _createUserUseCase.call(
      params: CreateUserInfoParams(
        fullName: state.name,
        password: passwordController.text.trim(),
        phoneNumber: state.phone.trim(),
        whatsAppNumber: state.whatsAppNumber.trim(),
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
    return super.close();
  }
}

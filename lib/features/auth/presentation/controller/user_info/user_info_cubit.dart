import 'package:equatable/equatable.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../core/utils/enums/general_status.dart';
import '../../../domain/use_cases/create_user_use_case.dart';
import '../../../../../generated/l10n.dart';

part 'user_info_state.dart';

class UserInfoCubit extends Cubit<UserInfoState> {
  final CreateUserUseCase _createUserUseCase;

  static const int totalSteps = 1;

  UserInfoCubit(this._createUserUseCase) : super(const UserInfoState());

  // ── Step navigation ────────────────────────────────────────────────────────

  void nextStep({required String password, required String confirmPassword}) {
    final error = _validateAccountStep(
      password: password,
      confirmPassword: confirmPassword,
    );
    if (error != null) {
      emit(state.copyWith(stepError: error));
      return;
    }
    _submitRegistration(
      password: password,
      confirmPassword: confirmPassword,
    );
  }

  void backStep() {
    if (state.step == 0) return;
    final prev = state.step - 1;
    emit(state.copyWith(step: prev, stepError: ''));
  }

  void clearStepError() => emit(state.copyWith(stepError: ''));
  void resetStatus() =>
      emit(state.copyWith(status: GeneralStatus.initialized, errorMessage: ''));

  // ── Setters ────────────────────────────────────────────────────────────────

  void setName(String v) => emit(state.copyWith(name: v));
  void setPhone(String v) => emit(state.copyWith(phone: v));
  void setWhatsAppNumber(String v) => emit(state.copyWith(whatsAppNumber: v));

  // ── Validators ─────────────────────────────────────────────────────────────

  String? _validateAccountStep({
    required String password,
    required String confirmPassword,
  }) {
    if (state.name.trim().length < 2) return S.current.nameTooShortError;
    if (state.phone.trim().length < 7) return S.current.phoneRequired;
    if (state.whatsAppNumber.trim().length < 7) {
      return S.current.whatsAppNumberRequired;
    }
    final pw = password.trim();
    if (pw.isEmpty) return S.current.passwordEmptyError;

    if (!pw.contains(RegExp(r'[0-9]'))) {
      return S.current.passwordMissingNumberError;
    }
    if (pw != confirmPassword.trim()) {
      return S.current.passwordsDoNotMatch;
    }
    return null;
  }

  // ── Submit ─────────────────────────────────────────────────────────────────

  Future<void> _submitRegistration({
    required String password,
    required String confirmPassword,
  }) async {
    emit(state.copyWith(status: GeneralStatus.loading));

    final result = await _createUserUseCase.call(
      params: CreateUserInfoParams(
        fullName: state.name,
        phoneNumber: state.phone.trim(),
        whatsAppNumber: state.whatsAppNumber.trim(),
        password: password.trim(),
        passwordConfirmation: confirmPassword.trim(),
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
}

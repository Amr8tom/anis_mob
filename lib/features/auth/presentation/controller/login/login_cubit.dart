import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../generated/l10n.dart';
import '../../../domain/use_cases/guest_login_use_case.dart';
import '../../../domain/use_cases/login_user_use_case.dart';

part 'login_state.dart';

class LoginCubit extends Cubit<LoginState> {
  final LoginUserUseCase _loginUserUseCase;
  final GuestLoginUseCase _guestLoginUseCase;

  // Controllers live here so the form widget stays stateless
  final phoneController = TextEditingController();
  final passwordController = TextEditingController();
  final formKey = GlobalKey<FormState>();

  LoginCubit(
    this._loginUserUseCase,
    this._guestLoginUseCase,
  ) : super(const LoginState());

  // ── Password visibility ────────────────────────────────────────────────────

  void togglePasswordVisibility() => emit(
        state.copyWith(isPasswordHidden: !state.isPasswordHidden),
      );

  // ── Login ──────────────────────────────────────────────────────────────────

  Future<void> login() async {
    if (!(formKey.currentState?.validate() ?? false)) return;

    emit(state.copyWith(status: LoginStatus.loginLoading));

    final result = await _loginUserUseCase.call(
      params: LoginUserParams(
        phoneNumber: phoneController.text.trim(),
        password: passwordController.text.trim(),
      ),
    );

    result.fold(
      (failure) => emit(
        state.copyWith(
          status: LoginStatus.error,
          loginErrorMassage: failure.message ?? S.current.generalError,
        ),
      ),
      (_) => emit(state.copyWith(status: LoginStatus.loggedIn)),
    );
  }

  // ── Guest ──────────────────────────────────────────────────────────────────

  Future<void> continueAsGuest() async {
    emit(state.copyWith(status: LoginStatus.loginLoading));

    final result = await _guestLoginUseCase.call();

    result.fold(
      (failure) => emit(
        state.copyWith(
          status: LoginStatus.error,
          loginErrorMassage: failure.message ?? S.current.generalError,
        ),
      ),
      (_) => emit(state.copyWith(status: LoginStatus.guestLoggedIn)),
    );
  }

  // ── Reset ──────────────────────────────────────────────────────────────────

  void resetStatus() => emit(
        state.copyWith(
          status: LoginStatus.initialized,
          loginErrorMassage: '',
        ),
      );

  @override
  Future<void> close() {
    phoneController.dispose();
    passwordController.dispose();
    return super.close();
  }
}

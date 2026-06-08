import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../generated/l10n.dart';
import '../../../domain/use_cases/guest_login_use_case.dart';
import '../../../domain/use_cases/login_user_use_case.dart';

part 'login_state.dart';

class LoginCubit extends Cubit<LoginState> {
  final LoginUserUseCase _loginUserUseCase;
  final GuestLoginUseCase _guestLoginUseCase;



  LoginCubit(
    this._loginUserUseCase,
    this._guestLoginUseCase,
  ) : super(const LoginState());

  // ── Password visibility ────────────────────────────────────────────────────

  void togglePasswordVisibility() => emit(
        state.copyWith(isPasswordHidden: !state.isPasswordHidden),
      );

  // ── Login ──────────────────────────────────────────────────────────────────

  Future<void> login({
    required String phoneNumber,
    required String password,
  }) async {
    emit(state.copyWith(status: LoginStatus.loginLoading));

    final result = await _loginUserUseCase.call(
      params: LoginUserParams(
        phoneNumber: phoneNumber.trim(),
        password: password.trim(),
      ),
    );

    result.fold(
      (failure) => emit(
        state.copyWith(
          status: LoginStatus.error,
          loginErrorMassage: failure.message ?? S.current.generalError,
        ),
      ),
      (login) => emit(state.copyWith(
        status: LoginStatus.loggedIn,
        profileCompleted: login.profileCompleted,
      )),
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
}

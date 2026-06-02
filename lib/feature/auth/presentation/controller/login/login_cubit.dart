import 'dart:convert';
import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';

import '../../../../../core/local_storage/cache_helper.dart';
import '../../../../../core/local_storage/cache_keys.dart';
import '../../../../../generated/l10n.dart';
import '../../../domain/use_cases/login_user_use_case.dart';

part 'login_state.dart';

class LoginCubit extends Cubit<LoginState> {
  final LoginUserUseCase _loginUserUseCase;

  // Controllers live here so the form widget stays stateless
  final usernameController = TextEditingController();
  final passwordController = TextEditingController();
  final formKey            = GlobalKey<FormState>();

  LoginCubit(this._loginUserUseCase) : super(const LoginState());

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
        userName: usernameController.text.trim(),
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
      (loginData) async {
        if (loginData.accessToken != null) {
          await CacheHelper.putString(
            key: CacheKeys.token,
            value: loginData.accessToken!,
          );
        }
        emit(state.copyWith(status: LoginStatus.loggedIn));
      },
    );
  }

  // ── Reset ──────────────────────────────────────────────────────────────────

  void resetStatus() => emit(
        state.copyWith(
          status: LoginStatus.initialized,
          loginErrorMassage: '',
        ),
      );

  // ── Token validity check ───────────────────────────────────────────────────

  bool isTokenValid() {
    final token = CacheHelper.getString(key: CacheKeys.token);
    if (token == null) return false;

    try {
      final parts = token.split('.');
      if (parts.length != 3) return false;

      final payload = json.decode(
        utf8.decode(base64Url.decode(base64Url.normalize(parts[1]))),
      );

      final expiry = DateTime.fromMillisecondsSinceEpoch(
        (payload['exp'] as int) * 1000,
      );
      return DateTime.now().isBefore(expiry);
    } catch (_) {
      return false;
    }
  }

  @override
  Future<void> close() {
    usernameController.dispose();
    passwordController.dispose();
    return super.close();
  }
}

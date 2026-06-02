part of 'login_cubit.dart';

enum LoginStatus {
  initialized,
  registered,
  registerError,
  registerLoading,
  loggedIn,
  loginLoading,
  sendingOTP,
  settingPassword,
  passwordSet,
  otpCorrect,
  otpVerified,
  reSendingOTP,
  passwordNotMatch,
  passwordEmpty,
  logoutLoading,
  loggedOut,
  error,
  authenticateCanceled,
}

extension LoginStatusX on LoginStatus {
  bool get isInitialized => this == LoginStatus.initialized;

  bool get isRegistered => this == LoginStatus.registered;

  bool get isRegistering => this == LoginStatus.registerLoading;

  bool get isRegisterError => this == LoginStatus.registerError;

  bool get isLoggedIn => this == LoginStatus.loggedIn;

  bool get isLoggingIn => this == LoginStatus.loginLoading;

  bool get isLoggingOut => this == LoginStatus.logoutLoading;

  bool get isLoggedOut => this == LoginStatus.loggedOut;

  bool get isError => this == LoginStatus.error;

  bool get isSendingOTP => this == LoginStatus.sendingOTP;

  bool get isOtpCorrect => this == LoginStatus.otpCorrect;

  bool get isOTPVerified => this == LoginStatus.otpVerified;

  bool get isReSendingOTP => this == LoginStatus.reSendingOTP;

  bool get isLoginenticateCanceled => this == LoginStatus.authenticateCanceled;

  bool get isPasswordNotMatch => this == LoginStatus.passwordNotMatch;

  bool get isPasswordEmpty => this == LoginStatus.passwordEmpty;

  bool get isSettingPassword => this == LoginStatus.settingPassword;

  bool get isPasswordSet => this == LoginStatus.passwordSet;
}

class LoginState extends Equatable {
  final LoginStatus status;
  final String? otpId, token;
  // final List<ErrorDetail>? errors;
  final Map<String, dynamic>? errorsSetPassword;
  final bool? isRegistered;
  final bool isPasswordHidden;

  final String? loginErrorMassage;

  const LoginState({
    this.status = LoginStatus.initialized,
    this.isRegistered = false,
    this.otpId,
    this.token,
    // this.errors,
    this.errorsSetPassword,
    this.loginErrorMassage,
    this.isPasswordHidden = true,
  });

  /// copy with method
  LoginState copyWith({
    LoginStatus? status,
    String? otpId,
    String? token,
    // List<ErrorDetail>? errors,
    bool? isPasswordHidden,
    bool? isRegistered,
    Map<String, dynamic>? errorsSetPassword,
    String? loginErrorMassage,
  }) {
    return LoginState(
      status: status ?? this.status,
      otpId: otpId ?? this.otpId,
      token: token ?? this.token,
      // errors: errors ?? this.errors,
      isPasswordHidden: isPasswordHidden ?? this.isPasswordHidden,
      isRegistered: isRegistered ?? this.isRegistered,
      errorsSetPassword: errorsSetPassword ?? this.errorsSetPassword,
      loginErrorMassage: loginErrorMassage ?? this.loginErrorMassage,
    );
  }

  @override
  List<Object?> get props => [
    status,
    // errors,
    otpId,
    token,
    isRegistered,
    errorsSetPassword,
    loginErrorMassage,
    isPasswordHidden,
  ];
}

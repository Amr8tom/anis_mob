part of 'login_cubit.dart';

enum LoginStatus {
  initialized,
  loggedIn,
  guestLoggedIn,
  loginLoading,
  logoutLoading,
  loggedOut,
  error,
}

extension LoginStatusX on LoginStatus {
  bool get isInitialized => this == LoginStatus.initialized;

  bool get isLoggedIn => this == LoginStatus.loggedIn;

  bool get isGuest => this == LoginStatus.guestLoggedIn;

  bool get isLoggingIn => this == LoginStatus.loginLoading;

  bool get isLoggingOut => this == LoginStatus.logoutLoading;

  bool get isLoggedOut => this == LoginStatus.loggedOut;

  bool get isError => this == LoginStatus.error;
}

class LoginState extends Equatable {
  final LoginStatus status;
  final bool isPasswordHidden;
  final bool profileCompleted;

  final String? loginErrorMassage;

  const LoginState({
    this.status = LoginStatus.initialized,
    this.loginErrorMassage,
    this.isPasswordHidden = true,
    this.profileCompleted = true,
  });

  LoginState copyWith({
    LoginStatus? status,
    bool? isPasswordHidden,
    bool? profileCompleted,
    String? loginErrorMassage,
  }) {
    return LoginState(
      status: status ?? this.status,
      isPasswordHidden: isPasswordHidden ?? this.isPasswordHidden,
      profileCompleted: profileCompleted ?? this.profileCompleted,
      loginErrorMassage: loginErrorMassage ?? this.loginErrorMassage,
    );
  }

  @override
  List<Object?> get props => [
        status,
        loginErrorMassage,
        isPasswordHidden,
        profileCompleted,
      ];
}

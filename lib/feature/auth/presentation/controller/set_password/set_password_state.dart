// part of 'set_password_cubit.dart';
//
// enum SetPasswordStatus {
//   initialized,
//   settingPassword,
//   passwordSet,
//   passwordNotMatch,
//   passwordEmpty,
//   error,
// }
//
// extension SetPasswordStatusX on SetPasswordStatus {
//   bool get isInitialized => this == SetPasswordStatus.initialized;
//
//   bool get isSettingPassword => this == SetPasswordStatus.settingPassword;
//
//   bool get isPasswordSet => this == SetPasswordStatus.passwordSet;
//
//   bool get isPasswordNotMatch => this == SetPasswordStatus.passwordNotMatch;
//
//   bool get isPasswordEmpty => this == SetPasswordStatus.passwordEmpty;
//
//   bool get isError => this == SetPasswordStatus.error;
// }
//
// final class SetPasswordState extends Equatable {
//   final SetPasswordStatus status;
//   // final List<ErrorDetail>? errors;
//   final String? otpId;
//
//   SetPasswordState({
//     this.status = SetPasswordStatus.initialized,
//     // this.errors,
//     this.otpId,
//   });
//
//   /// copy with method
//   SetPasswordState copyWith({
//     SetPasswordStatus? status,
//     // List<ErrorDetail>? errors,
//   }) {
//     return SetPasswordState(
//       status: status ?? this.status,
//       // errors: errors ?? this.errors,
//     );
//   }
//
//   @override
//   List<Object?> get props => [status, otpId];
// }

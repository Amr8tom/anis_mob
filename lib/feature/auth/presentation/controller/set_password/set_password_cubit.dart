// import 'package:bloc/bloc.dart';
// import 'package:equatable/equatable.dart';
// import 'package:flutter/cupertino.dart';
// import '../../../domain/usecases/set_password_use_case.dart';
// part 'set_password_state.dart';
//
//
// class SetPasswordCubit extends Cubit<SetPasswordState> {
//   final SetPasswordUseCase _setPasswordUseCase;
//   final TextEditingController otpController = TextEditingController();
//   final TextEditingController passwordController = TextEditingController();
//   final TextEditingController confirmPasswordController = TextEditingController();
//   final GlobalKey<FormState> setPasswordFormKey = GlobalKey<FormState>();
//
//   SetPasswordCubit(this._setPasswordUseCase) : super( SetPasswordState());
//
//
//
// Future<void> setPassword() async {
//   emit(state.copyWith(status: SetPasswordStatus.settingPassword));
//   if (passwordController == null || confirmPasswordController == null) {
//     emit(state.copyWith(status: SetPasswordStatus.passwordEmpty));
//     return;
//   }
//   if (passwordController.text.trim() !=
//       confirmPasswordController.text.trim()) {
//     emit(state.copyWith(status: SetPasswordStatus.passwordNotMatch));
//   }
//   final result = await _setPasswordUseCase.call(
//     params: SetPasswordParams(
//       otp: otpController.text.trim(),
//       newPassword: confirmPasswordController.text.trim(),
//       id: state.otpId!,
//       firbaseToken: '',
//     ),
//   );
//
//   result.fold((failure) => emit(state.copyWith(status: SetPasswordStatus.error)), (
//     data,
//   ) {
//     emit(
//       state.copyWith(
//         status:
//             data.errors?.length == 0
//                 ? SetPasswordStatus.passwordSet
//                 : SetPasswordStatus.error,
//         // errors: data.errors,
//       ),
//     );
//   });
// }
//
// void dispose() {
//   otpController.dispose();
//   passwordController.dispose();
//   confirmPasswordController.dispose();
//   setPasswordFormKey.currentState?.dispose();
// }
// }
//

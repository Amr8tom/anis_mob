// import 'package:bloc/bloc.dart';
// import 'package:equatable/equatable.dart';
// import 'package:flutter/cupertino.dart';
// import 'package:meta/meta.dart';
// import 'package:teaa_clent_amralaa/core/utils/enums/general_status.dart';
//
// import '../../../domain/entities/pilgrimRegister.dart';
// import '../../../domain/usecases/forget_password_use_case.dart';
//
// part 'forget_password_state.dart';
//
// class ForgetPasswordCubit extends Cubit<ForgetPasswordState> {
//   final TextEditingController usernameController = TextEditingController();
//   final GlobalKey<FormState> forgetFormKey = GlobalKey<FormState>();
//   final ForgetPasswordUseCase _forgetPasswordUseCase;
//
//   ForgetPasswordCubit(this._forgetPasswordUseCase)
//       : super(const ForgetPasswordState());
//
//   /// forget password method
//   Future<void> forgetPassword() async {
//     if (forgetFormKey.currentState!.validate()) {
//       emit(state.copyWith(status: GeneralStatus.loading));
//       final result = await _forgetPasswordUseCase.call(
//         params: ForgetPasswordParams(username: usernameController.text.trim()),
//       );
//       result.fold(
//             (failure) => emit(state.copyWith(status: GeneralStatus.error)),
//             (data) =>
//             emit(
//               state.copyWith(
//                 status: GeneralStatus.success,
//                 otpId: data.otpId,
//               ),
//             ),
//       );
//     }
//
//     @override
//     dispose() {
//       usernameController.dispose();
//       forgetFormKey.currentState?.dispose();
//     }
//   }
// }

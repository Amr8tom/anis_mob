// import 'package:flutter/material.dart';
// import 'package:you_can/core/extentions/navigation_extension.dart';
// import '../../../../../common/widgets/dialogs/show_custom_pop_up.dart';
// import '../../../../../common/widgets/sizeboxs/Sizer.dart';
// import '../../../../../core/constants/app_sizes.dart';
// import '../../../../../core/constants/colors.dart';
// import '../../../../../core/routing/route_names.dart';
// import '../../../../../core/utils/validators.dart';
// import '../../../../../generated/l10n.dart';
// import '../auth_button.dart';
// import '../auth_text_filed.dart';
//
// class NewPasswordForm extends StatefulWidget {
//   const NewPasswordForm({super.key});
//
//   @override
//   State<NewPasswordForm> createState() => _NewPasswordFormState();
// }
//
// class _NewPasswordFormState extends State<NewPasswordForm> {
//   final _formKey = GlobalKey<FormState>();
//   final _newPasswordController = TextEditingController();
//   final _confirmPasswordController = TextEditingController();
//
//   @override
//   void dispose() {
//     _newPasswordController.dispose();
//     _confirmPasswordController.dispose();
//     super.dispose();
//   }
//
//   void _handleSavePassword(BuildContext context) {
//     if (_formKey.currentState!.validate()) {
//       // Form is valid, proceed with saving password
//       context.pushNamedAndRemoveUntil(
//         DRoutesName.navigationMenuRoute,
//         predicate: (Route<dynamic> route) => false,
//       );
//     }
//   }
//
//   @override
//   Widget build(BuildContext context) {
//     return Form(
//       key: _formKey,
//       child: Container(
//         decoration: BoxDecoration(
//           color: ColorRes.white,
//           borderRadius: BorderRadius.only(
//             topLeft: Radius.circular(AppSizes.borderRadiusXXLg),
//             topRight: Radius.circular(AppSizes.borderRadiusXXLg),
//           ),
//         ),
//         width: double.infinity,
//         padding: EdgeInsets.only(left: AppSizes.xl, right: AppSizes.xl),
//         child: SingleChildScrollView(
//           child: Column(
//             mainAxisAlignment: MainAxisAlignment.center,
//             children: [
//               const Sizer(height: 30),
//
//               /// Title
//               Text(
//                 S.current.newPassword,
//                 style: Theme.of(
//                   context,
//                 ).textTheme.headlineLarge!.copyWith(
//                   fontSize:AppSizes.fontSizeLg
//                 ),
//                 textAlign: TextAlign.center,
//                 maxLines: 5,
//               ),
//               /// make size
//               const Sizer(height: 10),
//
//               ///
//               AuthTextField(
//                 isPassword: true,
//                 validator: Validators.password,
//                 hint: S.current.pleaseEnterPassword,
//                 controller: _newPasswordController,
//                 prefixIcon: Icon(Icons.lock_open_sharp, color: ColorRes.grey),
//               ),
//               AuthTextField(
//                 isPassword: true,
//                 validator:
//                     (value) => Validators.confirmPassword(
//                       value,
//                       _newPasswordController.text,
//                     ),
//                 hint: S.current.repeatNewPassword,
//                 controller: _confirmPasswordController,
//                 prefixIcon: Icon(Icons.lock_open_sharp, color: ColorRes.grey),
//               ),
//
//               const Sizer(height: 20),
//               AuthButton(
//                 text: S.current.saveNewPassword,
//                 onPressed: () => _handleSavePassword(context),
//                 width: double.infinity,
//                 height: AppSizes.buttonHeight,
//                 textColor: ColorRes.white,
//                 backgroundColor: ColorRes.primary,
//               ),
//             ],
//           ),
//         ),
//       ),
//     );
//   }
// }

// import 'package:flutter/material.dart';
// import '../../../../core/constants/app_sizes.dart';
// import '../../../../core/constants/asset_resoures.dart';
// import '../widgets/login/login_form.dart';
// import '../widgets/new_password/new_password_form.dart';
//
// class NewPasswordScreen extends StatelessWidget {
//   const NewPasswordScreen({super.key});
//
//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       body: Stack(
//         children: [
//           ///  Background Image
//           Positioned(
//             top: 0,
//             left: 0,
//             right: 0,
//             child: Image.asset(
//               AssetRes.backGroundImage,
//               width: AppSizes.fullWidth,
//               fit: BoxFit.fitWidth,
//             ),
//           ),
//
//           Column(
//             children: [
//               /// Top section with illustration
//               Expanded(
//                 flex: 5,
//                 child: Padding(
//                   padding: EdgeInsets.symmetric(
//                     horizontal: AppSizes.xl,
//                     vertical: AppSizes.xl,
//                   ),
//                   child: Center(
//                     child: Image.asset(
//                       AssetRes.logoWithName,
//                       fit: BoxFit.contain,
//                     ),
//                   ),
//                 ),
//               ),
//
//               /// Bottom section with text content
//              const Expanded(flex:4, child: NewPasswordForm()),
//             ],
//           ),
//         ],
//       ),
//     );
//   }
// }

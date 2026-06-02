// import 'package:flutter/material.dart';
//
// import '../../../../core/constants/app_sizes.dart';
// import '../../../../core/constants/asset_resoures.dart';
// import '../widgets/otp/otp_form.dart';
//
// class OtpScreen extends StatelessWidget {
//   const OtpScreen({super.key});
//
//   @override
//   Widget build(BuildContext context) {
//     return  Scaffold(
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
//                       AssetRes.logoWithOutBG,
//                       fit: BoxFit.contain,
//                     ),
//                   ),
//                 ),
//               ),
//
//               /// Bottom section with text content
//               Expanded(flex: 4, child: OtpForm()),
//             ],
//           ),
//         ],
//       ),
//     );
//   }
// }

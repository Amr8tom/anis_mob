// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';

// import '../../../../core/constants/app_sizes.dart';
// import '../../../../core/constants/colors.dart';
// import '../../../language/presentation/controller/language_cubit.dart';

// class LanguageSwitcher extends StatelessWidget {
//   const LanguageSwitcher({Key? key}) : super(key: key);

//   @override
//   Widget build(BuildContext context) {
//     return BlocBuilder<LanguageCubit, LanguageState>(
//       builder: (context, state) {
//         final languageCubit = context.read<LanguageCubit>();

//         return GestureDetector(
//           onTap: () {
//             languageCubit.toggleLang();
//           },
//           child: Container(
//             padding: EdgeInsets.symmetric(
//               horizontal: AppSizes.md, 
//               vertical: AppSizes.sm,
//             ),
//             decoration: BoxDecoration(
//               color: ColorRes.white,
//               borderRadius: BorderRadius.circular(AppSizes.borderRadius),
//               boxShadow: [
//                 BoxShadow(
//                   color: ColorRes.black.withOpacity(0.1),
//                   blurRadius: 10,
//                   offset: const Offset(0, 3),
//                 ),
//               ],
//             ),
//             child: Row(
//               mainAxisSize: MainAxisSize.min,
//               children: [
//                 Icon(
//                   Icons.language, 
//                   color: ColorRes.primary, 
//                   size: AppSizes.iconSmall,
//                 ),
//                 SizedBox(width: AppSizes.xs),
//                 Text(
//                   languageCubit.showLang,
//                   style: TextStyle(
//                     color: ColorRes.primary,
//                     fontSize: AppSizes.textSmall,
//                     fontWeight: FontWeight.bold,
//                   ),
//                 ),
//               ],
//             ),
//           ),
//         );
//       },
//     );
//   }
// }

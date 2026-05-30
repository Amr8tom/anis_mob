import 'package:anis/feature/navigation/presentation/widgets/profile_header.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:anis/common/widgets/appbar/appbar.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/device/device_utility.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controllers/navigation_cubit.dart';

PreferredSizeWidget customAppBar({
  final bool isHeader = false,
  final bool showMenu = false,
  final bool showBackArrow = false,
  final double? height,
  final BuildContext? context,
  final GlobalKey<ScaffoldState>? scaffoldKey,
}) {
  final controller = context?.read<NavigationCubit>();

  return DAppBar(
    showBackArrow: showBackArrow,
    showMenu: showMenu,
    // bgColor: ColorRes.transparent,
    appHeight: height ?? DDeviceUtils.getAppBarHeight() ,
    actions: [
      const Sizer(width: 15),

      /// when profile show special skip and done button
      // IconButton(onPressed: (){}, icon:Icon(Icons.menu,color: ColorRes.white,)),
      isHeader ? ProfileHeader() : const Sizer(),
      const Spacer(),

      /// todo : remove comment form this stack to red point for unreaded notification
      Stack(
        children: [
          // Text("Sdsds"),
          // context.read<NavigationCubit>().state.notificationCount > 0
          //     ? Text(
          //       "${context.read<NavigationCubit>().state.notificationCount}",
          //       style: Theme.of(context).textTheme.bodyLarge?.copyWith(
          //         fontWeight: FontWeight.bold,
          //         color: ColorRes.error2,
          //
          //       ),
          //     )
          //     :const Sizer(),
          GestureDetector(onTap: (){
            context?.pushNamed(DRoutesName.notificationsRoute);
          },
              child: SvgPicture.asset(
                  AssetRes.notificationIcon, color: ColorRes.white)),
        ],
      ),
      const Sizer(width: 30),

      showMenu?GestureDetector(
          onTap: (){
            scaffoldKey?.currentState?.openDrawer();
          },
          child: Directionality(
              textDirection: S.current.localeee=='en'?TextDirection.ltr:TextDirection.rtl,

              child: SvgPicture.asset(AssetRes.menuIcon, color: ColorRes.white))):Sizer(),

      const Sizer(width: 15),
    ],
  );
}

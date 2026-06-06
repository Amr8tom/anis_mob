import 'package:anis/features/navigation/presentation/widgets/profile_header.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:anis/common/widgets/appbar/appbar.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/device/device_utility.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import '../../../../core/constants/colors.dart';

PreferredSizeWidget customAppBar({
  final bool isHeader = false,
  final bool showMenu = false,
  final bool showBackArrow = false,
  final double? height,
  final BuildContext? context,
  final GlobalKey<ScaffoldState>? scaffoldKey,
}) {
  return DAppBar(
    showBackArrow: showBackArrow,
    showMenu: showMenu,
    // bgColor: ColorRes.transparent,
    appHeight: height ?? DDeviceUtils.getAppBarHeight(),
    actions: [
      const Sizer(width: 15),
      isHeader ? const ProfileHeader() : const Sizer(),
      const Spacer(),
      GestureDetector(
        onTap: () {
          context?.pushNamed(DRoutesName.notificationsRoute);
        },
        child: SvgPicture.asset(
          AssetRes.notificationIcon,
          colorFilter: const ColorFilter.mode(ColorRes.white, BlendMode.srcIn),
        ),
      ),
      const Sizer(width: 30),
      showMenu
          ? GestureDetector(
              onTap: () {
                scaffoldKey?.currentState?.openDrawer();
              },
              child: SvgPicture.asset(
                AssetRes.menuIcon,
                colorFilter:
                    const ColorFilter.mode(ColorRes.white, BlendMode.srcIn),
              ),
            )
          : const Sizer(),
      const Sizer(width: 15),
    ],
  );
}

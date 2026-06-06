import 'package:anis/features/navigation/presentation/widgets/show_logout_dialog.dart';
import 'package:anis/features/navigation/presentation/widgets/side_menu_item.dart';
import 'package:flutter/material.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/asset_resoures.dart';
import '../../../../core/device/device_utility.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';
import 'drawer_logo_widget.dart';

class CustomSideMenu extends StatelessWidget {
  const CustomSideMenu({super.key});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      backgroundColor: Colors.white,
      width: DDeviceUtils.getScreenWidth(context) / 1.4,
      child: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Sizer(height: 20),
            Center(
              child: Image.asset(
                AssetRes.logoWithName,
                width: AppSizes.widthcontainer * 1.5,
              ),
            ),
            const Sizer(height: 32),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
              child: SideMenuItem(
                icon: AssetRes.sideHomeIcon,
                title: S.current.home,
                onTap: () {
                  context.pop();
                },
              ),
            ),
            const Sizer(height: 12),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
              child: SideMenuItem(
                isIcon: true,
                iconData: Icons.settings_outlined,
                icon: AssetRes.sidePrivaceyIcon,
                title: S.current.settings,
                onTap: () {
                  context.pop();
                  context.pushNamed(DRoutesName.settingsRoute);
                },
              ),
            ),
            const Sizer(height: 12),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
              child: SideMenuItem(
                icon: AssetRes.sidePrivaceyIcon,
                title: S.current.privacyPolicy,
                onTap: () {
                  context.pushNamed(DRoutesName.termsAndConditionRoute);
                },
              ),
            ),
            const Sizer(height: 12),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
              child: SideMenuItem(
                isIcon: true,
                iconData: Icons.logout_rounded,
                icon: AssetRes.sidePrivaceyIcon,
                title: S.current.logOut,
                onTap: () {
                  showLogoutDialog(context);
                },
              ),
            ),
            const Spacer(),
            const DrawerLogoWidget(),
            const Sizer(height: 12),
          ],
        ),
      ),
    );
  }
}

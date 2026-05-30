import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:anis/core/extentions/navigation_extension.dart';

import '../../../core/constants/app_sizes.dart';
import '../../../core/constants/asset_resoures.dart';
import '../../../core/constants/colors.dart';
import '../../../core/device/device_utility.dart';
import '../../../core/routing/route_names.dart';

import '../../../feature/navigation/presentation/widgets/profile_header.dart';
import '../sizeboxs/Sizer.dart';

class DAppBar extends StatelessWidget implements PreferredSizeWidget {
  DAppBar({
    super.key,
    this.title,
    this.showBackArrow = false,
    this.showMenu = false,
    this.centerTitle = true,
    this.leadingWidget,
    this.actions,
    this.bgColor,
    this.arrowBackColor = false,
    this.fontSize,
    this.appHeight,
    this.showBackGroundColor = false,
    this.doSomeThing,
    this.isHeader = false,
    this.scaffoldKey,
  });

  final String? title;
  final bool showBackArrow;
  final bool showBackGroundColor;
  final bool showMenu;
  final bool centerTitle;
  final double? fontSize;
  final bool arrowBackColor;
  final bool isHeader;
  final List<Widget>? actions;
  final Widget? leadingWidget;
  final Color? bgColor;
  final double? appHeight;
  final GlobalKey<ScaffoldState>? scaffoldKey;

  void Function()? doSomeThing;

  @override
  Widget build(BuildContext context) {
    // NavigationCubit is required for indx and screens, so we use watch/read directly
    // but DAppBar is used in screens where NavigationCubit might not be in scope.
    // The previous error was likely due to DAppBar trying to access NavigationCubit
    // when it's not provided in the specific route.

    return Stack(
      children: [
        Container(
          decoration: BoxDecoration(
            color: showBackGroundColor ? Colors.grey[200] : ColorRes.primary,
            boxShadow: [
              BoxShadow(
                color: ColorRes.gold.withOpacity(0.1),
                blurRadius: 10,
                spreadRadius: 2,
              ),
            ],
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(AppSizes.borderRadiusLarge * 1.5),
              bottomRight: Radius.circular(AppSizes.borderRadiusLarge * 1.5),
            ),
          ),
          height:  AppSizes.appBarHeight * 2.7
        ),
        Container(
          child: Column(
            children: [
              // const Sizer(height: 20),
              AppBar(
                elevation: 0.0,
                titleTextStyle: TextStyle(
                  fontWeight: FontWeight.w700,
                  fontSize: fontSize ?? 24.sp,
                  color: ColorRes.white,
                ),
                backgroundColor: bgColor ?? ColorRes.transparent,
                automaticallyImplyLeading: false,
                // leadingWidth: 85.w,
                leading:
                    showBackArrow
                        ? IconButton(
                          onPressed: () {
                            if (doSomeThing != null) {
                              doSomeThing!();
                            }
                            context.pop(); // Navigate back
                          },
                          icon: Icon(
                            Icons.arrow_back_ios_rounded,
                            // Use arrow_back_ios for a more modern look
                            size: AppSizes.iconMd,
                            color:
                                arrowBackColor
                                    ? ColorRes.white
                                    : ColorRes.white,
                          ),
                        )
                        : Padding(
                          padding: const EdgeInsets.only(right: 20.0),
                          child: leadingWidget,
                        ),
                title: Text(title ?? ""),
                centerTitle: centerTitle,
                actions:
                    actions ??
                    [
                      const Sizer(width: 10),
                      isHeader ? const ProfileHeader() : const Sizer(),
                      // const Spacer(),
const Sizer(width: 20),
                      IconButton(
                        onPressed: () {
                          context.pushNamed(
                            DRoutesName.notificationsRoute,
                          );
                        },
                        icon: SvgPicture.asset(
                          AssetRes.notificationIcon,
                          color: ColorRes.white,
                        ),

                      ),
                      const Sizer(width: 7),

                      showMenu?  IconButton(
                        onPressed: () {
                          debugPrint('Menu icon tapped');
                          if (scaffoldKey != null &&
                              scaffoldKey!.currentState != null) {
                            scaffoldKey!.currentState!.openDrawer();
                          } else {
                            Scaffold.of(context).openDrawer();
                          }
                        },
                        icon: SvgPicture.asset(
                          AssetRes.menuIcon,
                          color: ColorRes.white,
                        ),

                      ):Sizer(),

                      const Sizer(width: 10),
                    ],
                toolbarHeight: DDeviceUtils.getAppBarHeight().sp * 1,
              ),

              Container(
                height: 3,
                decoration: BoxDecoration(
                  color: ColorRes.error.withOpacity(0.2),
                  boxShadow: [
                    BoxShadow(
                      offset: Offset(0, 2),
                      color: ColorRes.primary.withOpacity(0.2),
                      blurRadius: 4,
                      spreadRadius: 0.2,
                    ),
                  ],
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      ColorRes.primary.withOpacity(0.2),
                      ColorRes.primary.withOpacity(0.1),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  @override
  Size get preferredSize =>
      Size.fromHeight(appHeight ?? AppSizes.appBarHeight *1.8);
}

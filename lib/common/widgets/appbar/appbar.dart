import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:anis/core/extentions/navigation_extension.dart';

import '../../../core/constants/app_sizes.dart';
import '../../../core/constants/colors.dart';
import '../../../core/routing/route_names.dart';

import '../../../features/navigation/presentation/widgets/profile_header.dart';

class DAppBar extends StatelessWidget implements PreferredSizeWidget {
  const DAppBar({
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

  final VoidCallback? doSomeThing;

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
      ),
      child: Container(
        decoration: BoxDecoration(
          color: bgColor ?? ColorRes.white,
          border: Border(
            bottom: BorderSide(
              color: ColorRes.anisLine,
              width: 1,
            ),
          ),
        ),
        child: SafeArea(
          bottom: false,
          child: SizedBox(
            height: AppSizes.appBarHeight,
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                // ── Leading ────────────────────────────────────────────
                if (showBackArrow)
                  _BackButton(onPressed: () {
                    if (doSomeThing != null) doSomeThing!();
                    context.pop();
                  })
                else if (isHeader)
                  const Padding(
                    padding: EdgeInsets.only(right: 8),
                    child: ProfileHeader(),
                  )
                else if (leadingWidget != null)
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: AppSizes.sm),
                    child: leadingWidget,
                  )
                else
                  SizedBox(width: AppSizes.md),

                // ── Title ──────────────────────────────────────────────
                Expanded(
                  child: title != null
                      ? Text(
                          title!,
                          textAlign:
                              centerTitle ? TextAlign.center : TextAlign.start,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: tt.titleMedium?.copyWith(
                            fontSize: fontSize ?? AppSizes.fontSizeMd,
                            fontWeight: FontWeight.w700,
                            color: ColorRes.anisNavy,
                            letterSpacing: -0.3,
                          ),
                        )
                      : const SizedBox.shrink(),
                ),

                // ── Actions ────────────────────────────────────────────
                if (actions != null)
                  ...actions!
                else if (!showBackArrow || isHeader) ...[
                  _NotificationButton(
                    onPressed: () => context.pushNamed(
                      DRoutesName.notificationsRoute,
                    ),
                  ),
                  if (showMenu)
                    _MenuButton(
                      onPressed: () {
                        if (scaffoldKey?.currentState != null) {
                          scaffoldKey!.currentState!.openDrawer();
                        } else {
                          Scaffold.of(context).openDrawer();
                        }
                      },
                    ),
                  SizedBox(width: AppSizes.sm),
                ] else
                  SizedBox(width: AppSizes.md + AppSizes.sm),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  Size get preferredSize => Size.fromHeight(
        (appHeight ?? AppSizes.appBarHeight) +
            MediaQueryData.fromView(
                    WidgetsBinding.instance.platformDispatcher.views.first)
                .padding
                .top,
      );
}

// ── Private sub-widgets ────────────────────────────────────────────────────

class _BackButton extends StatelessWidget {
  final VoidCallback onPressed;
  const _BackButton({required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(left: AppSizes.sm),
      child: Material(
        color: ColorRes.anisChipBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
        child: InkWell(
          onTap: onPressed,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
          child: Padding(
            padding: EdgeInsets.all(AppSizes.sm),
            child: Icon(
              Icons.arrow_back_ios_new_rounded,
              size: AppSizes.iconSm,
              color: ColorRes.anisNavy,
            ),
          ),
        ),
      ),
    );
  }
}

class _NotificationButton extends StatelessWidget {
  final VoidCallback onPressed;
  const _NotificationButton({required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return IconButton(
      onPressed: onPressed,
      icon: Stack(
        clipBehavior: Clip.none,
        children: [
          Icon(
            Icons.notifications_outlined,
            size: AppSizes.iconMd,
            color: ColorRes.anisNavy,
          ),
          Positioned(
            top: -2,
            right: -2,
            child: Container(
              width: 8,
              height: 8,
              decoration: const BoxDecoration(
                color: ColorRes.anisButtonGreen,
                shape: BoxShape.circle,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _MenuButton extends StatelessWidget {
  final VoidCallback onPressed;
  const _MenuButton({required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return IconButton(
      onPressed: onPressed,
      icon: Icon(
        Icons.menu_rounded,
        size: AppSizes.iconMd,
        color: ColorRes.anisNavy,
      ),
    );
  }
}

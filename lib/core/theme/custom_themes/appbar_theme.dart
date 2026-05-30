import 'package:flutter/material.dart';

import '../../constants/colors.dart';

class DAppBarTheme {
  DAppBarTheme._();

  static final lightAppBarTheme = AppBarTheme(
    elevation: 0,
    centerTitle: true,
    scrolledUnderElevation: 0,
    backgroundColor: ColorRes.black,
    surfaceTintColor: ColorRes.black,
    shadowColor: ColorRes.primaryBGAppBar,
    iconTheme: IconThemeData(color: ColorRes.black, size: 24),
    actionsIconTheme: IconThemeData(color: ColorRes.black, size: 24),
    titleTextStyle: TextStyle(
        fontSize: 32,
        // fontSize: 32.sp,
        fontFamily: 'Cairo',
        fontWeight: FontWeight.w700,
        color: ColorRes.black),
  );

  static final darkAppBarTheme = AppBarTheme(
    elevation: 0,
    centerTitle: false,
    scrolledUnderElevation: 0,
    backgroundColor: ColorRes.black,
    surfaceTintColor: ColorRes.black,
    shadowColor: ColorRes.black,
    iconTheme: IconThemeData(color: ColorRes.white, size: 24),
    actionsIconTheme: IconThemeData(color: ColorRes.white, size: 24),
    titleTextStyle: TextStyle( fontFamily: 'Cairo',
        fontSize: 32, fontWeight: FontWeight.w700, color: ColorRes.white),
  );
}

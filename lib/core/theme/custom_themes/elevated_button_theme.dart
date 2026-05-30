
import 'package:flutter/material.dart';

import '../../constants/colors.dart';

class DElevatedButtonTheme {
  DElevatedButtonTheme._();

  /// -- Light Theme
  static final ElevatedButtonThemeData lightElevatedButtonTheme =
      ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(
      side: const BorderSide(color: ColorRes.primary),
      // elevation: 2,
      backgroundColor: ColorRes.primary,
      padding: EdgeInsets.symmetric(horizontal: 15, vertical: 10),
      textStyle: TextStyle(
          color: Colors.white,
          fontFamily: "Cairo",
          fontSize: 21,
          fontWeight: FontWeight.bold),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
    ),
  );

  /// -- Dark Theme
  static final ElevatedButtonThemeData darkElevatedButtonTheme =
      ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(
      side: const BorderSide(color: ColorRes.primary),
      // elevation: 2,
      backgroundColor: ColorRes.primary,
      padding: EdgeInsets.symmetric(horizontal: 15, vertical: 5),
      textStyle: TextStyle(
          color: Colors.white,
          fontFamily: "Cairo",
          fontSize: 21,
          fontWeight: FontWeight.bold),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
    ),
  );
}

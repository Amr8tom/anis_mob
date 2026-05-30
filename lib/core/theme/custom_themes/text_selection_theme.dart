import 'package:flutter/material.dart';

import '../../constants/colors.dart';

class DTextSelectionTheme {
  DTextSelectionTheme();

  static TextSelectionThemeData lightTextSelection = TextSelectionThemeData(
    cursorColor: ColorRes.primary, // Cursor color
    selectionColor: ColorRes.primary.withOpacity(0.3), // Text selection color
    selectionHandleColor: ColorRes.primary, // Text selection handle color
  );

  static TextSelectionThemeData darkTextSelection = TextSelectionThemeData(
    cursorColor: ColorRes.primary, // Cursor color
    selectionColor: ColorRes.primary.withOpacity(0.3), // Text selection color
    selectionHandleColor: ColorRes.primary, // Text selection handle color
  );
}

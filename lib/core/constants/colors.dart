import 'package:flutter/material.dart';

class ColorRes {
  ColorRes._();

  /// App Basic Colors
  static const Color primary = Color(0xFF009D8B);
    static const Color primaryLight = Color(0xFF404040);
  static const Color scaffoldBG = Color(0xFFE7ECED);
  static const Color lightYellow = Color(0x38FFD700);
  static const Color lightYellow2 = Color(0xFFDCBA8D);
  static const Color yellow = Color(0xFFD4962E);
  static const Color deepYellow = Color(0xFFEDC61B);
  static const Color mealSelection = Color(0x26D4962E);
  static const Color mealBorder = Color(0xFFD4962E);
  static const Color primaryBGAppBar =
      Color(0x99B91C1C); // Slightly transparent Red
  static const Color primaryDark = Color(0xFF111827); // Strong Black
  static const Color accent = Color(0xFFE5E7EB); // Light Gray

  /// Special Colors
  static const Color gold = Color(0xffDDB351); // Gold
  static const Color gold2 = Color(0xffdda051); // Gold
  static const Color silver = Color(0xff9C9C9C); // Silver
  static const Color whiteLevel = Color(0xffEBEBEB); // Light White
  static const Color staticBlueColor = Color(0xff299cdb); // Accent Blue
  static const Color staticGreenColor = Color(0xff0ab39c); // Accent Blue
  static const Color staticVioletColor = Color(0xff405189); // Accent Blue
  static const Color staticRedColor = Color(0xfff06548); // Accent Blue
  static const Color staticYellowColor = Color(0xfff7b84b); // Accent Blue

  /// App Bar & Buttons
  static const Color appBarColor = Color(0xFFB91C1C); // Match primary color
  static const Color buttonPrimary = Color(0xFFB91C1C); // Dark Red Button
  static const Color buttonSecondary = Color(0xFF111827); // Strong Black Button
  static const Color buttonDisabled = Color(0xFFC4C4C4); // Gray Disabled

  /// Background Colors
  static const Color light = Color(0xFFF6F6F6); // Soft Light
  static const Color dark = Color(0xFF111827); // Strong Black
  static const Color primaryBackground = Color(0xFF111827); // Dark Background

  /// Text Colors
  static const Color textPrimary = Color(0xFFFFFFFF); // White Text
  static const Color textSecondary = Color(0xFFE5E7EB); // Light Gray Text
  static const Color textWhite = Colors.white;

  /// Borders
  static const Color borderPrimary = Color(0xFFD9D9D9);
  static const Color borderSecondary = Color(0xFFE6E6E6);
  static const Color borderTextFormField = Color(0xFFECECEC);

  /// Error and Validation Colors
  static const Color error = Color(0xFFB91C1C);
  static const Color error2 = Color(0xFFB91C1C);
  static const Color red = Color(0xFFFF4155);
  static const Color success = Color(0xFF388E3C);
  static const Color warning = Color(0xFFF57C00);
  static const Color info = Color(0xFF1976D2);

  /// Neutral Shades
  static const Color black = Color(0xFF37474F);
  static const Color realBlack = Color(0xFF232323);
  static const Color darkerGrey = Color(0xFF4F4F4F);
  static const Color darkGrey = Color(0xFF939393);
  static const Color grey = Color(0xFF62757F);
  static const Color grey2 = Color(0xFF6F7073);
  static const Color grey3 = Color(0xFFf6f4f7);
  static const Color grey6 = Color(0xfff3f3f9);
  static const Color greyForBorders = Color(0x1a292929);
  static const Color grey4 = Color(0xfff1f1f1);
  static const Color grey_F707340 = Color(0x406f7073);
  static const Color grey5= Color(0x1a6f7073);
  static const Color bgColorOfCategoryComponent = Color(0xFFFCFCFC);
  static const Color softGrey = Color(0xFFF4F4F4);
  static const Color lightGrey = Color(0xFFF1F1F1);
  static const Color white = Color(0xFFFFFFFF);
  static const Color green = Color(0xFF169A1A);

  static const Color transparent = Colors.transparent;

  /// ── Anis Brand Colors ──────────────────────────────────────────────────────
  /// Primary green used for headers, icons, active nav items
  static const Color anisGreen       = Color(0xFF0D7A4E);
  /// Bright green used for CTA buttons / accents
  static const Color anisButtonGreen = Color(0xFF14A800);
  /// Dark navy used for text headings and nav-bar labels
  static const Color anisNavy        = Color(0xFF1A1A2E);
  /// Mint background (scaffold / card backgrounds)
  static const Color anisMintBg      = Color(0xFFF2FFF8);
  /// Slightly-darker mint for card surfaces
  static const Color anisCardBg      = Color(0xFFEAFAF2);
  /// Divider / subtle border in Anis screens
  static const Color anisLine        = Color(0xFFD4EFE2);

  /// Session / status tag colours
  static const Color anisTagBlue     = Color(0xFFD0E8FF);
  static const Color anisTagBlueTxt  = Color(0xFF1A6FB8);
  static const Color anisTagYellow   = Color(0xFFFFF3CD);
  static const Color anisTagYellowTxt= Color(0xFFA07000);
  static const Color anisTagPink     = Color(0xFFFFDDE8);
  static const Color anisTagPinkTxt  = Color(0xFFB0004A);
  static const Color anisTagGreen    = Color(0xFFD4F5E5);
  static const Color anisTagGreenTxt = Color(0xFF0D7A4E);

  /// Avatar / placeholder palette used in buddy cards
  static const Color anisAvatarA     = Color(0xFFB8E8D0);
  static const Color anisAvatarB     = Color(0xFFFFD6A5);
  static const Color anisAvatarC     = Color(0xFFD6BFFF);
  static const Color anisAvatarD     = Color(0xFFFFB3C1);
  /// Dark green used for home header avatar background
  static const Color anisAvatarDark  = Color(0xFF0A5E3C);

  /// UI text palette used across Anis screens
  static const Color anisTextDark       = Color(0xFF1F2937); // primary dark text in cards
  static const Color anisTextSecondary  = Color(0xFF374151); // secondary / empty-state labels
  static const Color anisTextMuted      = Color(0xFF6B7280); // muted / meta info
  static const Color anisChipText       = Color(0xFF4B5563); // inactive chip / subtitle text
  static const Color anisHintText       = Color(0xFF9CA3AF); // placeholder / hint / offline dot

  /// Availability indicator dots
  static const Color anisOnlineGreen    = Color(0xFF22C55E); // buddy online
  static const Color anisBusyAmber      = Color(0xFFF59E0B); // buddy busy

  /// Subscription / badge accent (gold amber)
  static const Color anisGold           = Color(0xFFFBBF24);

  /// Inactive chip / secondary button background
  static const Color anisChipBg         = Color(0xFFF3F4F6);

  /// Error / destructive states (workspace full, logout button)
  static const Color anisErrorRed       = Color(0xFFEF4444);
  static const Color anisErrorRedBg     = Color(0xFFFEE2E2);
  static const Color anisWarningBg      = Color(0xFFFEF3C7); // amber tint bg

  // ── Auth / Splash dark screen palette ──────────────────────────────────────
  /// Dark gradient top — very dark forest green
  static const Color anisAuthBgTop    = Color(0xFF0A1F18);
  /// Dark gradient mid — dark navy-green
  static const Color anisAuthBgMid    = Color(0xFF0D1728);
  /// Dark gradient bottom — near black
  static const Color anisAuthBgBottom = Color(0xFF060A10);
  /// Card / input container background on dark screens
  static const Color anisAuthContainer = Color(0xFF162118);
  /// Subtle border on dark screens
  static const Color anisAuthBorder   = Color(0xFF1E3228);

  /// Gradient Colors
  static const Gradient linerGradient = LinearGradient(
    begin: Alignment(0, 0),
    end: Alignment(0.707, -0.707),
    colors: [
      Color(0xFFB91C1C), // Dark Red
      Color(0xFF111827), // Strong Black
    ],
  );

  /// Gradient Lists
  static const List<Color> giftGrad = [
    Colors.yellow,
    Colors.yellow,
    Colors.yellow,
    ColorRes.primary,
    ColorRes.primary,
    ColorRes.primary,
  ];
  static const List<Color> langGrad = [
    Colors.white,
    ColorRes.primaryLight,
    ColorRes.primary,
  ];
  static const List<Color> pointsGrad = [
    ColorRes.primary,
    ColorRes.primary,
    Colors.yellow,
  ];
  static const List<Color> profileGrad = [
    ColorRes.primary,
    ColorRes.primary,
    ColorRes.white,
  ];

  /// Gift Status Gradients
  static const List<Color> pendinggrad = [
    Colors.grey,
    Colors.yellow,
  ];
  static const List<Color> refusedGrad = [Colors.grey, Colors.red];
  // static const List<Color> acceptedGrad = [
  // Colors.grey,
  // Colors.green,
  // ];
}

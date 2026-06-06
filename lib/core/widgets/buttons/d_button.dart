import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import '../../constants/app_sizes.dart';
import '../../constants/colors.dart';

/// Reusable button component following the design system
/// Supports different variants, sizes, and states
class DButton extends StatelessWidget {
  const DButton({
    super.key,
    required this.text,
    required this.onPressed,
    this.variant = DButtonVariant.primary,
    this.size = DButtonSize.medium,
    this.isLoading = false,
    this.isDisabled = false,
    this.prefixIcon,
    this.suffixIcon,
    this.width,
    this.height,
    this.isArabic = false,
    this.useRoundedBorder = true,
    this.useShadow = true, // Add this parameter
    this.useBlur = false,
    this.blurSigma = 15.0,
    this.fontSize,
    this.borderRadius,
    this.padding,
  });

  /// Button text content
  final String text;

  /// Callback when button is pressed
  final VoidCallback? onPressed;

  /// Button style variant
  final DButtonVariant variant;

  /// Button size
  final DButtonSize size;

  /// Whether button is in loading state
  final bool isLoading;

  /// Whether button is disabled
  final bool isDisabled;

  /// Optional prefix icon
  final Widget? prefixIcon;

  /// Optional suffix icon
  final Widget? suffixIcon;

  /// Custom width (defaults to full width)
  final double? width;
  final double? height;

  /// Whether text is Arabic (affects font family)
  final bool isArabic;

  /// Whether to use rounded border (defaults to true)
  final bool useRoundedBorder;

  /// Whether to use shadow around the button (defaults to true)
  final bool useShadow;

  /// Whether to apply a backdrop blur effect (defaults to false)
  final bool useBlur;

  /// The blur intensity (defaults to 15.0)
  final double blurSigma;

  /// Custom font size (overrides the default based on size enum)
  final double? fontSize;

  /// Custom border radius (overrides the default based on size enum)
  final double? borderRadius;

  /// Custom padding (overrides the default based on size enum)
  final EdgeInsetsGeometry? padding;

  @override
  Widget build(BuildContext context) {
    final button = ElevatedButton(
      onPressed: _getOnPressed(),
      style: _getButtonStyle(),
      child: _buildContent(),
    );

    return SafeArea(
      child: SizedBox(
        width: width ?? double.infinity,
        height: height ?? _getHeight(),
        child: useBlur
            ? ClipRRect(
                borderRadius: BorderRadius.circular(
                  useRoundedBorder ? _getBorderRadius() : 0,
                ),
                child: BackdropFilter(
                  filter: ImageFilter.blur(
                    sigmaX: blurSigma,
                    sigmaY: blurSigma,
                  ),
                  child: button,
                ),
              )
            : button,
      ),
    );
  }

  VoidCallback? _getOnPressed() {
    if (isDisabled || isLoading) return null;
    return onPressed;
  }

  double _getHeight() {
    switch (size) {
      case DButtonSize.small:
        return 40.0.h;
      case DButtonSize.medium:
        return AppSizes.buttonHeight.h;
      case DButtonSize.large:
        return AppSizes.buttonHeight.h * 1.2;
    }
  }

  ButtonStyle _getButtonStyle() {
    return ElevatedButton.styleFrom(
      backgroundColor: _getBackgroundColor(),
      foregroundColor: _getForegroundColor(),
      disabledBackgroundColor: ColorRes.buttonDisabled,
      disabledForegroundColor: ColorRes.grey,
      elevation: useShadow ? _getElevation() : 0,
      shadowColor: useShadow
          ? ColorRes.black.withValues(alpha: 0.15)
          : Colors.transparent,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(
          borderRadius ?? (useRoundedBorder ? _getBorderRadius() : 0),
        ),
        side: _getBorderSide(),
      ),
      padding: padding ??
          EdgeInsets.symmetric(
            horizontal: _getHorizontalPadding(),
            vertical: _getVerticalPadding(),
          ),
    );
  }

  Color _getBackgroundColor() {
    switch (variant) {
      case DButtonVariant.primary:
        return ColorRes.primary;
      case DButtonVariant.secondary:
        return ColorRes.white;
      case DButtonVariant.outline:
        return Colors.transparent;
      case DButtonVariant.text:
        return Colors.transparent;
    }
  }

  Color _getForegroundColor() {
    switch (variant) {
      case DButtonVariant.primary:
        return ColorRes.white;
      case DButtonVariant.secondary:
        return ColorRes.primary;
      case DButtonVariant.outline:
        return ColorRes.primary;
      case DButtonVariant.text:
        return ColorRes.primary;
    }
  }

  double _getElevation() {
    switch (variant) {
      case DButtonVariant.primary:
        return 4; // Increased for more prominent shadow
      case DButtonVariant.secondary:
        return 2; // Increased for more prominent shadow
      case DButtonVariant.outline:
      case DButtonVariant.text:
        return 1; // Added slight shadow for outline and text variants
    }
  }

  double _getBorderRadius() {
    switch (size) {
      case DButtonSize.small:
        return AppSizes.borderRadiusSmall;
      case DButtonSize.medium:
        return AppSizes.borderRadiusLarge;
      case DButtonSize.large:
        return AppSizes.borderRadiusXXLg;
    }
  }

  BorderSide _getBorderSide() {
    switch (variant) {
      case DButtonVariant.primary:
      case DButtonVariant.text:
        return BorderSide.none;
      case DButtonVariant.secondary:
        return const BorderSide(color: ColorRes.borderPrimary, width: 1);
      case DButtonVariant.outline:
        return const BorderSide(color: ColorRes.primary, width: 1.5);
    }
  }

  double _getHorizontalPadding() {
    switch (size) {
      case DButtonSize.small:
        return AppSizes.sm;
      case DButtonSize.medium:
        return AppSizes.md;
      case DButtonSize.large:
        return AppSizes.xl;
    }
  }

  double _getVerticalPadding() {
    switch (size) {
      case DButtonSize.small:
        return AppSizes.xs;
      case DButtonSize.medium:
        return AppSizes.sm;
      case DButtonSize.large:
        return AppSizes.md;
    }
  }

  TextStyle _getTextStyle() {
    /// Button text styles
    TextStyle buttonLarge = TextStyle(
      fontSize: 16.sp,
      fontWeight: FontWeight.w600,
      color: ColorRes.white,
      height: 1.2,
    );

    TextStyle buttonLargeArabic = TextStyle(
      fontSize: 16.sp,
      fontWeight: FontWeight.w600,
      color: ColorRes.white,
      fontFamily: 'Arabic',
      height: 1.3,
    );

    TextStyle buttonMedium = TextStyle(
      fontSize: 14.sp,
      fontWeight: FontWeight.w500,
      color: ColorRes.white,
      height: 1.2,
    );

    TextStyle buttonMediumArabic = TextStyle(
      fontSize: 14.sp,
      fontWeight: FontWeight.w500,
      color: ColorRes.white,
      fontFamily: 'Arabic',
      height: 1.3,
    );
    TextStyle baseStyle;

    switch (size) {
      case DButtonSize.small:
        baseStyle = isArabic ? buttonMediumArabic : buttonMedium;
        break;
      case DButtonSize.medium:
        baseStyle = isArabic ? buttonLargeArabic : buttonLarge;
        break;
      case DButtonSize.large:
        baseStyle = isArabic ? buttonLargeArabic : buttonLarge;
        break;
    }

    return baseStyle.copyWith(
      color: _getForegroundColor(),
      fontSize: fontSize ?? baseStyle.fontSize,
    );
  }

  Widget _buildContent() {
    if (isLoading) {
      return SizedBox(
        height: _getIconSize(),
        width: _getIconSize(),
        child: CircularProgressIndicator(
          strokeWidth: 2,
          valueColor: AlwaysStoppedAnimation<Color>(_getForegroundColor()),
        ),
      );
    }

    final List<Widget> children = [];

    if (prefixIcon != null) {
      children.add(
        SizedBox(
          height: _getIconSize(),
          width: _getIconSize(),
          child: prefixIcon,
        ),
      );
      children.add(SizedBox(width: AppSizes.sm));
    }

    children.add(
      Flexible(
        child: Text(
          text,
          style: _getTextStyle(),
          textAlign: TextAlign.center,
          overflow: TextOverflow.ellipsis,
          maxLines: 1,
        ),
      ),
    );

    if (suffixIcon != null) {
      children.add(SizedBox(width: AppSizes.sm));
      children.add(
        SizedBox(
          height: _getIconSize(),
          width: _getIconSize(),
          child: suffixIcon,
        ),
      );
    }

    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      mainAxisSize: MainAxisSize.min,
      children: children,
    );
  }

  double _getIconSize() {
    switch (size) {
      case DButtonSize.small:
        return 16;
      case DButtonSize.medium:
        return 20;
      case DButtonSize.large:
        return 24;
    }
  }
}

/// Button style variants
enum DButtonVariant {
  /// Primary button with colored background
  primary,

  /// Secondary button with white background and border
  secondary,

  /// Outline button with transparent background and colored border
  outline,

  /// Text button with no background or border
  text,
}

/// Button sizes
enum DButtonSize {
  /// Small button (32dp height)
  small,

  /// Medium button (40dp height)
  medium,

  /// Large button (48dp height)
  large,
}

import 'package:flutter/material.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/constants/app_sizes.dart';

class AuthButton extends StatelessWidget {
  final String text;
  final VoidCallback? onPressed;
  final bool isLoading;
  final bool isEnabled;
  final Color? backgroundColor;
  final Color? textColor;
  final double? width;
  final double? height;
  final Widget? icon;
  final double? fontSize;
  const AuthButton({
    super.key,
    required this.text,
    this.onPressed,
    this.isLoading = false,
    this.isEnabled = true,
    this.backgroundColor,
    this.textColor,
    this.width,
    this.height,
    this.icon,
    this.fontSize,
  });

  @override
  Widget build(BuildContext context) {
    final bool canPress = isEnabled && !isLoading && onPressed != null;

    return Container(
      width: width ?? double.infinity,
      height: height ?? AppSizes.buttonHeight,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
        boxShadow: canPress
            ? [
                BoxShadow(
                  color: ColorRes.primary,
                  blurRadius: AppSizes.sm,
                  offset: const Offset(0, 2),
                ),
              ]
            : [],
      ),
      child: Material(
        color: canPress
            ? (backgroundColor ?? ColorRes.black)
            : ColorRes.grey.withValues(alpha: 0.3),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
        child: InkWell(
          onTap: canPress ? onPressed : null,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
          child: Container(
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              // vertical: AppSizes.padding/2,
            ),
            child: Center(
              child: isLoading
                  ? SizedBox(
                      width: AppSizes.iconMd,
                      height: AppSizes.iconMd,
                      child: CircularProgressIndicator(
                        color: textColor ?? ColorRes.white,
                        strokeWidth: 2,
                      ),
                    )
                  : Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        if (icon != null) ...[
                          icon!,
                          const Sizer(width: 4),
                        ],
                        Text(
                          text.toUpperCase(),
                          style: Theme.of(context)
                              .textTheme
                              .bodyLarge
                              ?.copyWith(
                                  color: ColorRes.white, fontSize: fontSize),
                        ),
                      ],
                    ),
            ),
          ),
        ),
      ),
    );
  }
}

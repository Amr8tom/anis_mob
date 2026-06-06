import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

class StepNextButton extends StatelessWidget {
  final bool isLast;
  final bool isLoading;
  final VoidCallback? onTap;

  const StepNextButton({
    super.key,
    required this.isLast,
    required this.isLoading,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        height: AppSizes.buttonHeight,
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: isLast
                ? [ColorRes.anisGold, ColorRes.anisButtonGreen]
                : [ColorRes.anisGreen, ColorRes.anisButtonGreen],
            begin: Alignment.centerRight,
            end: Alignment.centerLeft,
          ),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXXLg),
          boxShadow: [
            BoxShadow(
              color: (isLast ? ColorRes.anisGold : ColorRes.anisGreen)
                  .withValues(alpha: 0.38),
              blurRadius: 16,
              offset: const Offset(0, 5),
            ),
          ],
        ),
        child: Center(
          child: isLoading
              ? const SizedBox(
                  width: 22,
                  height: 22,
                  child: CircularProgressIndicator(
                    strokeWidth: 2.5,
                    valueColor: AlwaysStoppedAnimation<Color>(ColorRes.white),
                  ),
                )
              : Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      isLast ? S.current.startNow : S.current.next,
                      style: TextStyle(
                        fontFamily: 'Cairo',
                        fontSize: AppSizes.fontSizeSm,
                        fontWeight: FontWeight.bold,
                        color: ColorRes.white,
                      ),
                    ),
                    if (!isLast) ...[
                      const Sizer(width: 4),
                      Icon(
                        Icons.arrow_forward_ios,
                        color: ColorRes.white,
                        size: AppSizes.iconSm,
                      ),
                    ],
                  ],
                ),
        ),
      ),
    );
  }
}

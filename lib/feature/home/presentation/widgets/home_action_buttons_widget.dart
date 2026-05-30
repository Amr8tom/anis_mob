import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';

/// Two CTA buttons below the header:
///   • [primary]   "مسح QR"       → green background, opens workspace QR scanner
///   • [secondary] "ابحث عن أنيس" → light card background
class HomeActionButtonsWidget extends StatelessWidget {
  final VoidCallback onScanQr;
  final VoidCallback onSearchAnis;

  const HomeActionButtonsWidget({
    super.key,
    required this.onScanQr,
    required this.onSearchAnis,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.spaceBtwItems,
        AppSizes.padding,
        AppSizes.xs,
      ),
      child: Row(
        textDirection: TextDirection.rtl,
        children: [
          Expanded(
            child: HomeActionButton(
              label: S.current.scanQrShort,
              icon: Icons.qr_code_scanner_rounded,
              bgColor: ColorRes.anisButtonGreen,
              fgColor: ColorRes.white,
              onTap: onScanQr,
            ),
          ),
          const Sizer(width: 12),
          Expanded(
            child: HomeActionButton(
              label: S.current.searchForAnis,
              icon: Icons.people_alt_rounded,
              bgColor: ColorRes.anisChipBg,
              fgColor: ColorRes.anisNavy,
              onTap: onSearchAnis,
            ),
          ),
        ],
      ),
    );
  }
}

class HomeActionButton extends StatelessWidget {
  final String label;
  final IconData icon;
  final Color bgColor;
  final Color fgColor;
  final VoidCallback onTap;

  const HomeActionButton({
    super.key,
    required this.label,
    required this.icon,
    required this.bgColor,
    required this.fgColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        height: AppSizes.buttonHeight,
        decoration: BoxDecoration(
          color: bgColor,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withOpacity(0.07),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Row(
          textDirection: TextDirection.rtl,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: fgColor, size: AppSizes.iconMd),
            const Sizer(width: 8),
            Text(
              label,
              style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                    color: fgColor,
                    fontWeight: FontWeight.w700,
                  ),
            ),
          ],
        ),
      ),
    );
  }
}

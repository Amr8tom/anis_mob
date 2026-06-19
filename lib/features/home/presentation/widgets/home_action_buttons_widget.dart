import 'package:flutter/material.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';

/// Two CTA buttons below the header.
///
/// When NOT checked-in:
///   [primary] "Scan QR": green, opens QR scanner.
///   [secondary] "Find Buddy": light, navigates to buddy tab.
///
/// When CHECKED-IN:
///   Single full-width "Leave Workspace" button.
class HomeActionButtonsWidget extends StatelessWidget {
  final VoidCallback onScanQr;
  final VoidCallback onSubscribe;
  final VoidCallback onLeaveWorkspace;
  final bool isCheckedIn;
  final bool isLoading;
  final bool isCheckoutPending;

  const HomeActionButtonsWidget({
    super.key,
    required this.onScanQr,
    required this.onSubscribe,
    required this.onLeaveWorkspace,
    this.isCheckedIn = false,
    this.isLoading = false,
    this.isCheckoutPending = false,
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
      child: isCheckedIn
          // Pending owner approval: disabled amber "awaiting approval" button.
          ? isCheckoutPending
              ? HomeActionButton(
                  label: S.current.awaitingCheckoutApproval,
                  icon: Icons.hourglass_top_rounded,
                  bgColor: ColorRes.anisChipBg,
                  fgColor: ColorRes.anisNavy,
                  onTap: null,
                )
              // Checked-in: leave workspace (or request to leave)
              : HomeActionButton(
                  label: isLoading
                      ? S.current.checkingOut
                      : S.current.leaveWorkspace,
                  icon: Icons.logout_rounded,
                  bgColor: ColorRes.anisErrorRed,
                  fgColor: ColorRes.white,
                  onTap: isLoading ? null : onLeaveWorkspace,
                )
          // Idle: scan + find buddy
          : Row(
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
                    label: S.current.subscribe,
                    icon: Icons.workspace_premium_rounded,
                    bgColor: ColorRes.anisChipBg,
                    fgColor: ColorRes.anisNavy,
                    onTap: onSubscribe,
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
  final VoidCallback? onTap;

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
              color: ColorRes.anisNavy.withValues(alpha: 0.07),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: fgColor, size: AppSizes.iconMd),
            const Sizer(width: 8),
            Flexible(
              child: Text(
                label,
                overflow: TextOverflow.ellipsis,
                style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                      color: fgColor,
                      fontWeight: FontWeight.w700,
                      fontSize: 14,
                    ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/generated/l10n.dart';

/// Minimalist RTL-aware bottom sheet shown when a guest tries a
/// restricted action. Call via [showGuestRestrictedSheet].
class GuestRestrictedSheet extends StatelessWidget {
  const GuestRestrictedSheet({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.vertical(
          top: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.md,
        AppSizes.padding,
        AppSizes.ld,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // ── Drag handle ───────────────────────────────────────
          Center(
            child: Container(
              width: 40.w,
              height: 4.h,
              decoration: BoxDecoration(
                color: ColorRes.anisLine,
                borderRadius: BorderRadius.circular(2.r),
              ),
            ),
          ),

          const Sizer(height: 24),

          // ── Lock icon ─────────────────────────────────────────
          Center(
            child: Container(
              width: AppSizes.iconXLarge,
              height: AppSizes.iconXLarge,
              decoration: const BoxDecoration(
                color: ColorRes.anisCardBg,
                shape: BoxShape.circle,
              ),
              child: Icon(
                Icons.lock_outline_rounded,
                color: ColorRes.anisGreen,
                size: AppSizes.iconMd,
              ),
            ),
          ),

          const Sizer(height: 16),

          // ── Title ─────────────────────────────────────────────
          Text(
            S.current.guestRestrictedTitle,
            textAlign: TextAlign.center,
            style: tt.titleMedium?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.anisTextDark,
            ),
          ),

          const Sizer(height: 8),

          // ── Body ──────────────────────────────────────────────
          Text(
            S.current.guestRestrictedBody,
            textAlign: TextAlign.center,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.anisTextMuted,
              height: 1.6,
            ),
          ),

          const Sizer(height: 28),

          // ── Login / Register ──────────────────────────────────
          SizedBox(
            height: AppSizes.buttonHeight,
            child: ElevatedButton(
              onPressed: () {
                Navigator.of(context).pop();
                context.pushReplacementNamed(DRoutesName.loginRoute);
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: ColorRes.anisGreen,
                foregroundColor: ColorRes.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
              ),
              child: Text(
                S.current.loginOrRegister,
                style: tt.titleSmall?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
              ),
            ),
          ),

          const Sizer(height: 12),

          // ── Cancel ────────────────────────────────────────────
          SizedBox(
            height: AppSizes.buttonHeight,
            child: TextButton(
              onPressed: () => Navigator.of(context).pop(),
              style: TextButton.styleFrom(
                foregroundColor: ColorRes.anisTextMuted,
                overlayColor: ColorRes.anisChipBg,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                  side: const BorderSide(color: ColorRes.anisInputBorder),
                ),
              ),
              child: Text(
                S.current.cancel,
                style: tt.bodyMedium?.copyWith(
                  color: ColorRes.anisTextMuted,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ),

          const Sizer(height: 8),
        ],
      ),
    );
  }
}

/// Shows the [GuestRestrictedSheet] as a modal bottom sheet.
/// RTL direction is inherited from the app's locale automatically.
Future<void> showGuestRestrictedSheet(BuildContext context) {
  return showModalBottomSheet(
    context: context,
    backgroundColor: ColorRes.transparent,
    isScrollControlled: true,
    useSafeArea: true,
    builder: (_) => const GuestRestrictedSheet(),
  );
}

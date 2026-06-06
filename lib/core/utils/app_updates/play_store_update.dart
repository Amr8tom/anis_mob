import 'package:flutter/material.dart';
import 'package:in_app_update/in_app_update.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/widgets/buttons/d_button.dart';

import '../../../generated/l10n.dart';
import '../../constants/app_sizes.dart';
import '../../constants/colors.dart';

Future<void> checkForPlayUpdate({required BuildContext context}) async {
  try {
    final updateInfo = await InAppUpdate.checkForUpdate();
    if (!context.mounted) return;
    if (updateInfo.updateAvailability == UpdateAvailability.updateAvailable) {
      _showUpdateDialog(
        context: context,
        versionCode: updateInfo.availableVersionCode?.toString(),
      );
    }
  } catch (_) {}
}

void _showUpdateDialog({
  required BuildContext context,
  String? versionCode,
}) {
  showDialog(
    context: context,
    barrierDismissible: false,
    barrierColor: Colors.black54,
    builder: (context) {
      return Dialog(
        backgroundColor: Colors.transparent,
        elevation: 0,
        child: Container(
          decoration: BoxDecoration(
            color: ColorRes.white,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLarge),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: 0.15),
                blurRadius: 24,
                offset: const Offset(0, 8),
              ),
            ],
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // ── Colored header ──────────────────────────────────────────
              Container(
                width: double.infinity,
                padding: EdgeInsets.symmetric(vertical: AppSizes.md * 1.5),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      ColorRes.primary,
                      ColorRes.primary.withValues(alpha: 0.75),
                    ],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(AppSizes.borderRadiusLarge),
                    topRight: Radius.circular(AppSizes.borderRadiusLarge),
                  ),
                ),
                child: Column(
                  children: [
                    // Icon with glowing circle
                    Container(
                      width: 72,
                      height: 72,
                      decoration: BoxDecoration(
                        color: ColorRes.white.withValues(alpha: 0.2),
                        shape: BoxShape.circle,
                      ),
                      child: Icon(
                        Icons.system_update_alt_rounded,
                        color: ColorRes.white,
                        size: AppSizes.iconXLarge,
                      ),
                    ),
                    const Sizer(height: 12),
                    Text(
                      S.current.updateAvailable,
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                            color: ColorRes.white,
                            fontWeight: FontWeight.bold,
                          ),
                    ),
                    if (versionCode != null) ...[
                      const Sizer(height: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 4,
                        ),
                        decoration: BoxDecoration(
                          color: ColorRes.white.withValues(alpha: 0.25),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          'v$versionCode',
                          style:
                              Theme.of(context).textTheme.bodySmall?.copyWith(
                                    color: ColorRes.white,
                                    fontWeight: FontWeight.w600,
                                    letterSpacing: 0.5,
                                  ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),

              // ── Body ────────────────────────────────────────────────────
              Padding(
                padding: EdgeInsets.all(AppSizes.padding * 1.25),
                child: Column(
                  children: [
                    Text(
                      S.current.updateBody,
                      textAlign: TextAlign.center,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                            color: ColorRes.grey2,
                            height: 1.5,
                          ),
                    ),
                    const Sizer(height: 24),

                    // ── Update Now button ──────────────────────────────────
                    DButton(
                      text: S.current.update,
                      height: AppSizes.heightcontainer,
                      borderRadius: AppSizes.borderRadiusXXLg,
                      size: DButtonSize.medium,
                      variant: DButtonVariant.primary,
                      onPressed: () async {
                        Navigator.of(context).pop();
                        try {
                          await InAppUpdate.performImmediateUpdate();
                        } catch (_) {}
                      },
                    ),
                    const Sizer(height: 10),

                    // ── Later button ───────────────────────────────────────
                    DButton(
                      text: S.current.later,
                      height: AppSizes.heightcontainer,
                      borderRadius: AppSizes.borderRadiusXXLg,
                      size: DButtonSize.medium,
                      variant: DButtonVariant.secondary,
                      onPressed: () => Navigator.of(context).pop(),
                    ),
                    const Sizer(height: 4),
                  ],
                ),
              ),
            ],
          ),
        ),
      );
    },
  );
}

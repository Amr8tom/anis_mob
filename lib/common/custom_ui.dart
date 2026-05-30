import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:lottie/lottie.dart';
import '../../common/widgets/sizeboxs/Sizer.dart';
import '../core/constants/app_sizes.dart';
import '../core/constants/asset_resoures.dart';
import '../core/constants/colors.dart';
import '../generated/l10n.dart';

class CustomUI {
  static loader({required BuildContext context}) {
    return showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return Center(
          child: RepaintBoundary(
            child: Lottie.asset(AssetRes.loaderLottie, height: 30.h),
          ),
        );
      },
    );
  }

  static void showLoadingDialog(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder:
          (_) => Center(
            child: RepaintBoundary(
              child: Lottie.asset(AssetRes.loaderLottie, width: 100.w),
            ),
          ),
    );
  }

  static void showFailureDialog(BuildContext context, {String? message}) {
    showDialog(
      context: context,
      barrierDismissible: true,
      builder:
          (_) => AlertDialog(
            title: Text(S.current.error),
            content: Text(message ?? S.current.error),
            actions: [
              TextButton(
                onPressed: () => Navigator.of(context).pop(),
                child: Text(S.current.done),
              ),
            ],
          ),
    );
  }

  static Widget simpleLoader() {
    return Center(
      child: RepaintBoundary(
        child: Lottie.asset(AssetRes.loaderLottie, width: 100.w),
      ),
    );
  }



  // ── Anis design-system states ────────────────────────────────────────────────

  /// Generic empty state used across all Anis feature screens.
  /// Call as: CustomUI.anisEmptyState(context: context, icon: ..., title: ..., subtitle: ...)
  static Widget anisEmptyState({
    required BuildContext context,
    required IconData icon,
    required String title,
    String? subtitle,
  }) {
    final tt = Theme.of(context).textTheme;
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            icon,
            size: AppSizes.iconXLarge,
            color: ColorRes.anisGreen.withOpacity(0.35),
          ),
          const SizedBox(height: 16),
          Text(
            title,
            textAlign: TextAlign.center,
            style: tt.bodyLarge?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.anisTextSecondary,
            ),
          ),
          if (subtitle != null) ...[
            const SizedBox(height: 6),
            Text(
              subtitle,
              textAlign: TextAlign.center,
              style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
            ),
          ],
        ],
      ),
    );
  }

  /// Generic error state used across all Anis feature screens.
  /// Call as: CustomUI.anisErrorState(context: context, message: ..., onRetry: ...)
  static Widget anisErrorState({
    required BuildContext context,
    required String message,
    required VoidCallback onRetry,
  }) {
    final tt = Theme.of(context).textTheme;
    return Center(
      child: Padding(
        padding: EdgeInsets.symmetric(horizontal: AppSizes.xl),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.wifi_off_rounded,
              size: AppSizes.iconXLarge,
              color: ColorRes.anisHintText,
            ),
            const SizedBox(height: 16),
            Text(
              message,
              textAlign: TextAlign.center,
              style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: onRetry,
              style: ElevatedButton.styleFrom(
                backgroundColor: ColorRes.anisGreen,
                foregroundColor: ColorRes.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusLg),
                ),
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.xl,
                  vertical: AppSizes.sm,
                ),
              ),
              child: Text(
                S.current.retry,
                style: tt.bodySmall?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  static Widget simpleSendingDataLoader() {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          RepaintBoundary(
            child: Lottie.asset(AssetRes.loaderLottie, width: 100.w),
          ),
          const Sizer(height: 8),
          Text(S.current.sending),
        ],
      ),
    );
  }

  static Widget mapLoader() {
    return RepaintBoundary(
      child: Center(child: Lottie.asset(AssetRes.loaderLottie, width: 200.w)),
    );
  }

  static Widget simpleFailure() {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          RepaintBoundary(
            child: Lottie.asset(
              AssetRes.error404Lottie,
              height: AppSizes.productItemHeight,
            ),
          ),
          SizedBox(height: AppSizes.spaceBtwItems),
          Text(
            S.current.error,
            style: TextStyle(
              color: ColorRes.error,
              fontSize: 16.sp,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  static Widget emptyData({String? message}) {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          RepaintBoundary(
            child: ColorFiltered(
              colorFilter: ColorFilter.mode(
                ColorRes.primary.withOpacity(0.7),
                BlendMode.srcIn,
              ),
              child: Lottie.asset(
                AssetRes.noData,
                height: AppSizes.productItemHeight,
              ),
            ),
          ),
          SizedBox(height: AppSizes.spaceBtwItems),
          Text(
            message ?? S.current.noData,
            style: TextStyle(
              color: ColorRes.primary,
              fontSize: 16.sp,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  static Widget tryLater() {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(S.current.tryLater),
          RepaintBoundary(
            child: Lottie.asset(AssetRes.error404Lottie, width: 100.w),
          ),
        ],
      ),
    );
  }

  static Widget noData() {
    return Center(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          RepaintBoundary(
            child: ColorFiltered(
              colorFilter: ColorFilter.mode(
                ColorRes.primary.withOpacity(0.7),
                BlendMode.srcIn,
              ),
              child: Lottie.asset(
                AssetRes.noData,
                height: AppSizes.productItemHeight,
              ),
            ),
          ),
          SizedBox(height: AppSizes.spaceBtwItems*6),
          Text(S.current.noData),
        ],
      ),
    );
  }

  static ScaffoldFeatureController<SnackBar, SnackBarClosedReason>
  snackBarSuccess({required BuildContext context, String? message}) {
    return ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message ?? S.current.done),
        backgroundColor: Colors.green,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  static ScaffoldFeatureController<SnackBar, SnackBarClosedReason>
  snackBarFailure({required BuildContext context, String? message}) {
    return ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message ?? S.current.error,
          style: Theme.of(
            context,
          ).textTheme.bodySmall?.copyWith(color: ColorRes.white),
        ),
        backgroundColor: ColorRes.error,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }
}

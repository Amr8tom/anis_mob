import 'package:flutter/material.dart';
import '../constants/colors.dart';
import '../constants/app_sizes.dart';
import '../extentions/navigation_extension.dart';

/// Centralized UI utility class for common UI patterns
/// Provides consistent loading, error, and empty state handling
class CustomUI {
  CustomUI._();

  /// Show loading dialog
  static void loader({required BuildContext context}) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => Center(
        child: Container(
          padding: EdgeInsets.all(AppSizes.xl),
          decoration: BoxDecoration(
            color: ColorRes.white,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          ),
          child: CircularProgressIndicator(
            valueColor: AlwaysStoppedAnimation<Color>(ColorRes.primary),
          ),
        ),
      ),
    );
  }

  /// Show simple loading indicator
  static Widget simpleLoader() {
    return Center(
      child: CircularProgressIndicator(
        valueColor: AlwaysStoppedAnimation<Color>(ColorRes.primary),
      ),
    );
  }

  /// Show error message with localized text
  static void showError({
    required BuildContext context,
    required String message,
  }) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: Theme.of(context).textTheme.bodyMedium!.copyWith(color: ColorRes.white),
        ),
        backgroundColor: ColorRes.error,
        behavior: SnackBarBehavior.floating,
        margin: EdgeInsets.all(AppSizes.md),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        ),
      ),
    );
  }

  /// Show success message
  static void showSuccess({
    required BuildContext context,
    required String message,
  }) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: Theme.of(context).textTheme.bodyMedium!.copyWith(color: ColorRes.white),
        ),
        backgroundColor: ColorRes.success,
        behavior: SnackBarBehavior.floating,
        margin: EdgeInsets.all(AppSizes.md),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        ),
      ),
    );
  }

  /// Empty state create_request_support
  static Widget emptyState({
    required BuildContext context,
    required String message,
    String? title,
    Widget? icon,
    VoidCallback? onRetry,
    String? retryText,
  }) {
    return Center(
      child: Padding(
        padding: EdgeInsets.all(AppSizes.xl),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            icon ??
                Icon(
                  Icons.inbox_outlined,
                  size: AppSizes.iconXLarge * 2,
                  color: ColorRes.grey,
                ),
            SizedBox(height: AppSizes.xl),
            if (title != null) ...[
              Text(
                title,
                style: Theme.of(context).textTheme.headlineMedium!.copyWith(
                  color: ColorRes.textPrimary,
                ),
                textAlign: TextAlign.center,
              ),
              SizedBox(height: AppSizes.sm),
            ],
            Text(
              message,
              style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                color: ColorRes.textSecondary,
              ),
              textAlign: TextAlign.center,
            ),
            if (onRetry != null) ...[
              SizedBox(height: AppSizes.xl),
              ElevatedButton(
                onPressed: onRetry,
                style: ElevatedButton.styleFrom(
                  backgroundColor: ColorRes.primary,
                  padding: EdgeInsets.symmetric(
                    horizontal: AppSizes.xl,
                    vertical: AppSizes.md,
                  ),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                  ),
                ),
                child: Text(
                  retryText ?? 'Retry',
                  style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                    color: ColorRes.white,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }

  /// Hide loading dialog
  static void hideLoader(BuildContext context) {
    context.pop();
  }
}

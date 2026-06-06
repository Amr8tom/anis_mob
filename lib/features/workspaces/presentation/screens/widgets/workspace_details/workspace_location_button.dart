import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart' as url_launcher;

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceLocationButton extends StatelessWidget {
  final double latitude;
  final double longitude;
  final String label;

  const WorkspaceLocationButton({
    super.key,
    required this.latitude,
    required this.longitude,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: GestureDetector(
        onTap: () => _openMaps(context),
        child: Container(
          padding: EdgeInsets.symmetric(
            horizontal: AppSizes.md,
            vertical: AppSizes.sm,
          ),
          decoration: BoxDecoration(
            color: ColorRes.anisGreen,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.map_outlined, color: ColorRes.white),
              const Sizer(width: 8),
              Text(
                label,
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: ColorRes.white,
                      fontWeight: FontWeight.w700,
                    ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _openMaps(BuildContext context) async {
    final url =
        'https://www.google.com/maps/search/?api=1&query=$latitude,$longitude';
    try {
      await url_launcher.launchUrl(Uri.parse(url));
    } catch (_) {
      if (!context.mounted) return;
      CustomUI.snackBarFailure(context: context, message: S.current.error);
    }
  }
}

import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart' as url_launcher;
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceLocationCard extends StatelessWidget {
  final double latitude;
  final double longitude;
  final String address;

  const WorkspaceLocationCard({
    super.key,
    required this.latitude,
    required this.longitude,
    required this.address,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Container(
        padding: EdgeInsets.all(AppSizes.md),
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withValues(alpha: 0.04),
              blurRadius: 10,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  Icons.location_on_rounded,
                  color: ColorRes.anisGreen,
                  size: AppSizes.iconSm,
                ),
                Sizer(width: AppSizes.xs),
                Text(
                  S.current.location,
                  style: tt.titleSmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisNavy,
                  ),
                ),
              ],
            ),
            Sizer(height: AppSizes.xs + 2),
            Text(
              address,
              textAlign: TextAlign.start,
              style: tt.bodySmall?.copyWith(
                color: ColorRes.anisTextMuted,
              ),
            ),
            Sizer(height: AppSizes.md),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: () => _openMaps(context),
                style: ElevatedButton.styleFrom(
                  backgroundColor: ColorRes.anisGreen,
                  foregroundColor: ColorRes.white,
                  padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 2),
                  shape: RoundedRectangleBorder(
                    borderRadius:
                        BorderRadius.circular(AppSizes.borderRadiusLg),
                  ),
                  elevation: 0,
                ),
                icon: const Icon(Icons.map_outlined, size: 18),
                label: Text(
                  S.current.openInMaps,
                  style: tt.bodyMedium?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.white,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _openMaps(BuildContext context) async {
    final url =
        'https://www.google.com/maps/search/?api=1&query=$latitude,$longitude';
    final uri = Uri.parse(url);
    if (await url_launcher.canLaunchUrl(uri)) {
      await url_launcher.launchUrl(
        uri,
        mode: url_launcher.LaunchMode.externalApplication,
      );
    }
  }
}

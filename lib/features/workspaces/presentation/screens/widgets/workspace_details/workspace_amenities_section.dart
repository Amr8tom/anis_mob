import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceAmenitiesSection extends StatelessWidget {
  final List<String> amenities;
  const WorkspaceAmenitiesSection({super.key, required this.amenities});

  static const _icons = <String, IconData>{
    'wifi': Icons.wifi_rounded,
    'ac': Icons.ac_unit_rounded,
    'coffee': Icons.local_cafe_rounded,
    'printing': Icons.print_rounded,
    'quiet': Icons.volume_off_rounded,
  };

  String _getLabel(String key) {
    switch (key) {
      case 'wifi':
        return S.current.amenityWifi;
      case 'ac':
        return S.current.amenityAc;
      case 'coffee':
        return S.current.amenityCoffee;
      case 'printing':
        return S.current.amenityPrinting;
      case 'quiet':
        return S.current.amenityQuiet;
      default:
        return key;
    }
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.amenities,
            textAlign: TextAlign.start,
            style: tt.titleSmall?.copyWith(
              fontWeight: FontWeight.w700,
              color: ColorRes.anisNavy,
            ),
          ),
          Sizer(height: AppSizes.sm),
          Wrap(
            spacing: AppSizes.sm,
            runSpacing: AppSizes.sm,
            alignment: WrapAlignment.start,
            children: amenities.map((key) {
              return Container(
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.md,
                  vertical: AppSizes.xs + 2,
                ),
                decoration: BoxDecoration(
                  color: ColorRes.anisCardBg,
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                  border: Border.all(color: ColorRes.anisLine),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(
                      _icons[key] ?? Icons.check_circle_outline,
                      size: AppSizes.iconSm,
                      color: ColorRes.anisGreen,
                    ),
                    Sizer(width: AppSizes.xs),
                    Text(
                      _getLabel(key),
                      style: tt.bodySmall?.copyWith(
                        fontWeight: FontWeight.w600,
                        color: ColorRes.anisTextDark,
                      ),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }
}

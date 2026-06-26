import 'package:flutter/material.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';

class NotificationPreferenceTile extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final bool value;
  final bool isSaving;
  final ValueChanged<bool> onChanged;

  const NotificationPreferenceTile({
    super.key,
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.value,
    required this.isSaving,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    final textTheme = Theme.of(context).textTheme;

    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.md),
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.anisMintBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(color: ColorRes.anisLine),
      ),
      child: Row(
        children: [
          Container(
            width: 46,
            height: 46,
            decoration: BoxDecoration(
              color: ColorRes.anisButtonGreen.withValues(alpha: 0.10),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            child: Icon(icon, color: ColorRes.anisGreen),
          ),
          const Sizer(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: textTheme.bodyLarge?.copyWith(
                    color: ColorRes.anisTextDark,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const Sizer(height: 4),
                Text(
                  subtitle,
                  style: textTheme.bodySmall?.copyWith(
                    color: ColorRes.anisTextMuted,
                    height: 1.35,
                  ),
                ),
              ],
            ),
          ),
          const Sizer(width: 10),
          Switch.adaptive(
            value: value,
            activeColor: ColorRes.anisButtonGreen,
            onChanged: isSaving ? null : onChanged,
          ),
        ],
      ),
    );
  }
}

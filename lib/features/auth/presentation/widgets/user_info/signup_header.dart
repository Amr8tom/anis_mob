import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

class SignUpHeader extends StatelessWidget {
  const SignUpHeader({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      width: double.infinity,
      color: ColorRes.anisGreen,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        MediaQuery.of(context).padding.top + 16,
        AppSizes.padding,
        20,
      ),
      child: Row(
        children: [
          // App icon
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: ColorRes.white,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisNavy.withValues(alpha: 0.18),
                  blurRadius: 10,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            padding: EdgeInsets.all(AppSizes.xs),
            child: Image.asset(AssetRes.logo, fit: BoxFit.contain),
          ),
          const Sizer(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                S.current.register,
                style: tt.titleMedium?.copyWith(
                  fontWeight: FontWeight.w800,
                  color: ColorRes.white,
                  fontSize: 17,
                ),
              ),
              Text(
                S.current.appTagline,
                style: tt.bodySmall?.copyWith(
                  color: ColorRes.white.withValues(alpha: 0.72),
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

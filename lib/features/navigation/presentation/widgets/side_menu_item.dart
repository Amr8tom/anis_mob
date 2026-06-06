import 'package:flutter/material.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';

class SideMenuItem extends StatelessWidget {
  final String icon;
  final String title;
  final VoidCallback onTap;
  final bool isIcon;
  final IconData? iconData;

  const SideMenuItem({
    super.key,
    required this.icon,
    required this.title,
    required this.onTap,
    this.iconData,
    this.isIcon = false,
  });

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        child: Container(
          decoration: BoxDecoration(
            color: ColorRes.primary.withValues(alpha: 0.06),
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            border: Border.all(
              color: ColorRes.primary.withValues(alpha: 0.10),
              width: 1,
            ),
          ),
          padding: EdgeInsets.symmetric(
            horizontal: AppSizes.padding,
            vertical: AppSizes.padding,
          ),
          child: Row(
            children: [
              Container(
                width: AppSizes.iconLg * 1.5,
                height: AppSizes.iconLg * 1.5,
                alignment: Alignment.center,
                decoration: BoxDecoration(
                  color: ColorRes.primary.withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
                ),
                child: isIcon
                    ? Icon(
                        iconData,
                        color: ColorRes.primary,
                        size: AppSizes.iconMd,
                      )
                    : Image.asset(
                        icon,
                        color: ColorRes.primary,
                        width: AppSizes.iconMd,
                        height: AppSizes.iconMd,
                      ),
              ),
              const Sizer(width: 14),
              Expanded(
                child: Text(
                  title,
                  style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                        color: ColorRes.black,
                        fontWeight: FontWeight.w700,
                      ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              Icon(
                Icons.arrow_forward_ios_rounded,
                size: AppSizes.iconSm,
                color: ColorRes.primary.withValues(alpha: 0.6),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';

import '../../../core/constants/app_sizes.dart';
import '../../../core/constants/asset_resoures.dart';
import '../../../core/constants/colors.dart';
import '../../../features/navigation/presentation/controllers/navigation_cubit.dart';
import '../../../generated/l10n.dart';

class CustomBottomNavigationBar extends StatelessWidget {
  const CustomBottomNavigationBar({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = context.watch<NavigationCubit>();

    final items = [
      _NavItem(icon: AssetRes.home, label: S.current.homeTab),
      _NavItem(icon: AssetRes.groups, label: S.current.buddiesTab),
      _NavItem(icon: AssetRes.services, label: S.current.workspacesTab),
      _NavItem(icon: AssetRes.profile, label: S.current.profileTab),
    ];

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withValues(alpha: 0.08),
            blurRadius: 20,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        top: false,
        child: SizedBox(
          height: 60.h,
          child: Row(
            children: List.generate(items.length, (index) {
              final isActive = controller.indx == index;
              return Expanded(
                child: _NavTabItem(
                  item: items[index],
                  isActive: isActive,
                  onTap: () =>
                      context.read<NavigationCubit>().changeIndex(index),
                ),
              );
            }),
          ),
        ),
      ),
    );
  }
}

// ── Single tab item ───────────────────────────────────────────────────────────

class _NavTabItem extends StatelessWidget {
  final _NavItem item;
  final bool isActive;
  final VoidCallback onTap;

  const _NavTabItem({
    required this.item,
    required this.isActive,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final activeColor = ColorRes.anisGreen;
    final inactiveColor = ColorRes.anisHintText;
    final color = isActive ? activeColor : inactiveColor;

    return GestureDetector(
      onTap: onTap,
      behavior: HitTestBehavior.opaque,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // ── Active indicator pill at the very top ─────────
          AnimatedContainer(
            duration: const Duration(milliseconds: 250),
            curve: Curves.easeOutCubic,
            height: 3.h,
            width: isActive ? 24.w : 0,
            margin: EdgeInsets.only(bottom: 6.h),
            decoration: BoxDecoration(
              color: activeColor,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
            ),
          ),

          // ── Icon ─────────────────────────────────────────
          AnimatedScale(
            scale: isActive ? 1.08 : 1.0,
            duration: const Duration(milliseconds: 200),
            child: SvgPicture.asset(
              item.icon,
              width: 22.w,
              height: 22.w,
              colorFilter: ColorFilter.mode(color, BlendMode.srcIn),
            ),
          ),

          SizedBox(height: 4.h),

          // ── Label ────────────────────────────────────────
          AnimatedDefaultTextStyle(
            duration: const Duration(milliseconds: 200),
            style: TextStyle(
              fontSize: isActive ? 11.sp : 10.5.sp,
              fontWeight: isActive ? FontWeight.w700 : FontWeight.w500,
              color: color,
            ),
            child: Text(item.label),
          ),
        ],
      ),
    );
  }
}

// ── Data class ────────────────────────────────────────────────────────────────

class _NavItem {
  final String icon;
  final String label;
  const _NavItem({required this.icon, required this.label});
}

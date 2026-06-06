import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import 'package:anis/features/auth/presentation/controller/user_info/user_info_cubit.dart';

/// Single gender selection card.
/// Calls [UserInfoCubit.selectGender] on tap — no logic here.
class StepGenderCard extends StatelessWidget {
  final String emoji;
  final String label;
  final int id;
  final int? selectedId;
  final Color accentColor;

  const StepGenderCard({
    super.key,
    required this.emoji,
    required this.label,
    required this.id,
    required this.selectedId,
    required this.accentColor,
  });

  bool get _isSelected => selectedId == id;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => context.read<UserInfoCubit>().selectGender(id),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 220),
        curve: Curves.easeOut,
        height: 100.h,
        decoration: BoxDecoration(
          color: _isSelected
              ? accentColor.withValues(alpha: 0.18)
              : ColorRes.anisAuthContainer,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          border: Border.all(
            color: _isSelected ? accentColor : ColorRes.anisAuthBorder,
            width: _isSelected ? 2 : 1,
          ),
          boxShadow: _isSelected
              ? [
                  BoxShadow(
                    color: accentColor.withValues(alpha: 0.28),
                    blurRadius: 14,
                    offset: const Offset(0, 4),
                  ),
                ]
              : null,
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(emoji, style: TextStyle(fontSize: 28.sp)),
            const Sizer(width: 8),
            Text(
              label,
              style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: _isSelected ? accentColor : ColorRes.white,
                  ),
            ),
            if (_isSelected) ...[
              const Sizer(width: 6),
              Icon(Icons.check_circle_rounded, color: accentColor, size: 18.sp),
            ],
          ],
        ),
      ),
    );
  }
}

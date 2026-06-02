import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

class StepHeaderWidget extends StatelessWidget {
  final int step;
  final List<({IconData icon, String label})> meta;

  const StepHeaderWidget({super.key, required this.step, required this.meta});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(20.w, 16.h, 20.w, 8.h),
      child: Column(
        children: [
          Row(
            children: List.generate(meta.length, (i) {
              final done   = i < step;
              final active = i == step;
              return Expanded(
                child: Row(
                  children: [
                    if (i > 0)
                      Expanded(
                        child: AnimatedContainer(
                          duration: const Duration(milliseconds: 350),
                          height: 2.h,
                          color: i <= step
                              ? ColorRes.anisGreen
                              : ColorRes.anisAuthBorder,
                        ),
                      ),
                    AnimatedContainer(
                      duration: const Duration(milliseconds: 350),
                      width:  active ? 36.w : 28.w,
                      height: active ? 36.w : 28.w,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: done || active
                            ? ColorRes.anisGreen
                            : ColorRes.anisAuthBorder,
                        boxShadow: active
                            ? [
                                BoxShadow(
                                  color: ColorRes.anisGreen.withValues(alpha: 0.50),
                                  blurRadius: 12,
                                  spreadRadius: 2,
                                ),
                              ]
                            : null,
                      ),
                      child: Center(
                        child: done
                            ? Icon(Icons.check_rounded,
                                color: ColorRes.white, size: 14.sp)
                            : Icon(meta[i].icon,
                                color: active
                                    ? ColorRes.white
                                    : ColorRes.white.withValues(alpha: 0.40),
                                size: active ? 16.sp : 13.sp),
                      ),
                    ),
                    if (i < meta.length - 1)
                      Expanded(
                        child: AnimatedContainer(
                          duration: const Duration(milliseconds: 350),
                          height: 2.h,
                          color: i < step
                              ? ColorRes.anisGreen
                              : ColorRes.anisAuthBorder,
                        ),
                      ),
                  ],
                ),
              );
            }),
          ),
          const Sizer(height: 4),
          Text(
            S.current.stepIndicator(step + 1, meta.length, meta[step].label),
            style: Theme.of(context).textTheme.labelSmall?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.55),
              letterSpacing: 0.3,
            ),
          ),
        ],
      ),
    );
  }
}

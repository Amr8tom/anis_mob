import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../controller/home_cubit.dart';
import 'location_selector_bottom_sheet.dart';

class HomeHeaderWidget extends StatelessWidget {
  final UserProfileEntity profile;

  const HomeHeaderWidget({super.key, required this.profile});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Stack(
      clipBehavior: Clip.hardEdge,
      children: [
        // ── Main Background Container with Forest-Green Gradient ──
        Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [
                Color(0xFF095A39), // Rich Deep Emerald
                Color(0xFF0D7A4E), // Brand Green
              ],
              begin: Alignment.topRight,
              end: Alignment.bottomLeft,
            ),
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(32),
              bottomRight: Radius.circular(32),
            ),
          ),
          padding: EdgeInsets.only(
            top: MediaQuery.of(context).padding.top + AppSizes.sm,
            left: AppSizes.padding,
            right: AppSizes.padding,
            bottom: AppSizes.ld + 4.h,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // ── Top bar: greeting + subscription badge (left) & Streak + Star (right) ──
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Name + greeting column
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          _greeting(),
                          style: tt.bodySmall?.copyWith(
                            color: ColorRes.white.withValues(alpha: 0.65),
                            fontWeight: FontWeight.w500,
                            letterSpacing: 0.2,
                          ),
                        ),
                        const Sizer(height: 3),
                        Text(
                          profile.name,
                          textAlign: TextAlign.start,
                          style: tt.headlineMedium?.copyWith(
                            color: ColorRes.white,
                            fontWeight: FontWeight.w900,
                            fontSize: 24.sp,
                            shadows: [
                              Shadow(
                                color: Colors.black.withValues(alpha: 0.15),
                                blurRadius: 4.r,
                                offset: const Offset(0, 2),
                              ),
                            ],
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const Sizer(height: 10),
                        // Subscription badge — inline under name
                        _SubscriptionBadge(type: profile.subscriptionType),
                      ],
                    ),
                  ),
                  const Sizer(width: 14),
                  // Streak & Star Column
                  Column(
                    children: [
                      _StreakBadge(streakDays: profile.streakDays),
                      const Sizer(height: 10),
                      const _StarIconContainer(),
                    ],
                  ),
                ],
              ),

              const Sizer(height: 14),

              // Location selector pill
              BlocBuilder<HomeCubit, HomeState>(
                builder: (context, state) {
                  final locationText =
                      state.locationName ?? 'تحديد الموقع الحالي';
                  return Align(
                    alignment: AlignmentDirectional.centerStart,
                    child: InkWell(
                      onTap: () => LocationSelectorBottomSheet.show(context),
                      borderRadius: BorderRadius.circular(20.r),
                      child: Container(
                        padding: EdgeInsets.symmetric(
                          horizontal: 12.w,
                          vertical: 6.h,
                        ),
                        decoration: BoxDecoration(
                          color: ColorRes.white.withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(20.r),
                          border: Border.all(
                            color: ColorRes.white.withValues(alpha: 0.18),
                            width: 1,
                          ),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              Icons.location_on_rounded,
                              size: 14.r,
                              color: ColorRes.anisGold,
                            ),
                            const Sizer(width: 6),
                            Text(
                              locationText,
                              style: tt.bodySmall?.copyWith(
                                color: ColorRes.white,
                                fontWeight: FontWeight.w700,
                                fontFamily: 'Cairo',
                                fontSize: 11.sp,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const Sizer(width: 4),
                            Icon(
                              Icons.keyboard_arrow_down_rounded,
                              size: 14.r,
                              color: ColorRes.white.withValues(alpha: 0.7),
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                },
              ),

              const Sizer(height: 24),

              // ── Days Remaining Progress section ──
              Align(
                alignment: AlignmentDirectional.centerStart,
                child: Text(
                  _remainingText(context),
                  style: tt.bodySmall?.copyWith(
                    color: ColorRes.white.withValues(alpha: 0.8),
                    fontFamily: 'Cairo',
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
              const Sizer(height: 8),

              // Custom premium gradient progress bar with shadow glow
              Container(
                height: 8.h,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: ColorRes.white.withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(4.r),
                ),
                child: LayoutBuilder(
                  builder: (context, constraints) {
                    final progress = profile.subscriptionProgress;
                    return Align(
                      alignment: AlignmentDirectional.centerStart,
                      child: Container(
                        width: constraints.maxWidth * progress,
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [
                              Color(0xFFFBBF24), // Gold
                              Color(0xFFF59E0B), // Warm Amber
                            ],
                            begin: Alignment.centerRight,
                            end: Alignment.centerLeft,
                          ),
                          borderRadius: BorderRadius.circular(4.r),
                          boxShadow: [
                            BoxShadow(
                              color: const Color(0xFFFBBF24)
                                  .withValues(alpha: 0.45),
                              blurRadius: 6.r,
                              offset: const Offset(0, 1),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
              ),

              const Sizer(height: 8),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    _totalDaysText(context),
                    style: tt.bodySmall?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.7),
                      fontFamily: 'Cairo',
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  Text(
                    _usedDaysText(context),
                    style: tt.bodySmall?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.7),
                      fontFamily: 'Cairo',
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),

        // ── Top Right Decorative Ambient Glow Circle ──
        Positioned(
          top: -30.h,
          right: -30.w,
          child: IgnorePointer(
            child: Container(
              width: 120.r,
              height: 120.r,
              decoration: BoxDecoration(
                color: ColorRes.white.withValues(alpha: 0.04),
                shape: BoxShape.circle,
              ),
            ),
          ),
        ),

        // ── Mid Left Decorative Ambient Glow Circle ──
        Positioned(
          bottom: 20.h,
          left: -40.w,
          child: IgnorePointer(
            child: Container(
              width: 140.r,
              height: 140.r,
              decoration: BoxDecoration(
                color: ColorRes.white.withValues(alpha: 0.03),
                shape: BoxShape.circle,
              ),
            ),
          ),
        ),
      ],
    );
  }

  String _greeting() {
    final hour = DateTime.now().hour;
    if (hour < 12) return '👋 ${S.current.goodMorning}';
    if (hour < 17) return '👋 ${S.current.goodAfternoon}';
    return '👋 ${S.current.goodEvening}';
  }

  String _remainingText(BuildContext context) {
    final remainingDays = profile.subscriptionDaysRemaining;
    final remainingHours = profile.subscriptionRemainingHours;
    return S.current.subscriptionDaysAndHours(remainingDays, remainingHours);
  }

  String _usedDaysText(BuildContext context) {
    final used =
        profile.subscriptionTotalDays - profile.subscriptionDaysRemaining;
    return S.current.subscriptionDaysUsed(used);
  }

  String _totalDaysText(BuildContext context) {
    final total = profile.subscriptionTotalDays;
    return S.current.subscriptionTotalDaysLabel(total);
  }
}

// Helper function to convert digits to Arabic numerals
String _toArabicDigits(String input) {
  const english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
  for (int i = 0; i < english.length; i++) {
    input = input.replaceAll(english[i], arabic[i]);
  }
  return input;
}

// ── Streak badge chip ────────────────────────────────────────────────────────
class _StreakBadge extends StatelessWidget {
  final int streakDays;
  const _StreakBadge({required this.streakDays});

  @override
  Widget build(BuildContext context) {
    final isAr = Localizations.localeOf(context).languageCode == 'ar';
    final label =
        isAr ? 'م.${_toArabicDigits(streakDays.toString())}' : 'S.$streakDays';

    return Container(
      width: 38.r,
      height: 38.r,
      decoration: BoxDecoration(
        color: Colors.black.withValues(alpha: 0.22),
        shape: BoxShape.circle,
        border: Border.all(
          color: ColorRes.white.withValues(alpha: 0.15),
          width: 1,
        ),
      ),
      alignment: Alignment.center,
      child: Text(
        label,
        style: TextStyle(
          color: ColorRes.white,
          fontWeight: FontWeight.w800,
          fontSize: 13.sp,
          fontFamily: 'Cairo',
        ),
      ),
    );
  }
}

// ── Star icon container ──────────────────────────────────────────────────────
class _StarIconContainer extends StatelessWidget {
  const _StarIconContainer();

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 52.r,
      height: 52.r,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            ColorRes.white.withValues(alpha: 0.15),
            ColorRes.white.withValues(alpha: 0.05),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(14.r),
        border: Border.all(
          color: ColorRes.white.withValues(alpha: 0.18),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 4.r,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      alignment: Alignment.center,
      child: Icon(
        Icons.star_rounded,
        size: 32.r,
        color: ColorRes.anisGold,
        shadows: [
          Shadow(
            color: ColorRes.anisGold.withValues(alpha: 0.5),
            blurRadius: 8.r,
          ),
        ],
      ),
    );
  }
}

// ── Subscription badge chip ──────────────────────────────────────────────────
class _SubscriptionBadge extends StatelessWidget {
  final String type;
  const _SubscriptionBadge({required this.type});

  String _label() {
    switch (type) {
      case 'gold':
        return S.current.goldSubscription;
      case 'free':
        return S.current.freeSubscription;
      default:
        return S.current.silverSubscription;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: 10.w,
        vertical: 4.h,
      ),
      decoration: BoxDecoration(
        color: Colors.black.withValues(alpha: 0.18),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.white.withValues(alpha: 0.15),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 6.r,
            height: 6.r,
            decoration: BoxDecoration(
              color: ColorRes.anisGold,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisGold.withValues(alpha: 0.8),
                  blurRadius: 4.r,
                  spreadRadius: 1.r,
                ),
              ],
            ),
          ),
          const Sizer(width: 6),
          Text(
            _label(),
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisGold,
                  fontWeight: FontWeight.w800,
                  fontSize: 11.sp,
                  fontFamily: 'Cairo',
                ),
          ),
        ],
      ),
    );
  }
}

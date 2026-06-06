import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../domain/entity/user_profile_entity.dart';

class HomeHeaderWidget extends StatelessWidget {
  final UserProfileEntity profile;

  const HomeHeaderWidget({super.key, required this.profile});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.anisGreen,
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(AppSizes.borderRadiusXXLg),
          bottomRight: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
      padding: EdgeInsets.only(
        top: MediaQuery.of(context).padding.top + AppSizes.sm,
        left: AppSizes.padding,
        right: AppSizes.padding,
        bottom: AppSizes.ld,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // ── Top bar: greeting + avatar ─────────────────────────────
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.center,
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
                      ),
                    ),
                    const Sizer(height: 3),
                    Text(
                      profile.name,
                      textAlign: TextAlign.start,
                      style: tt.headlineMedium?.copyWith(
                        color: ColorRes.white,
                        fontWeight: FontWeight.w800,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const Sizer(height: 6),
                    // Subscription badge — inline under name
                    _SubscriptionBadge(type: profile.subscriptionType),
                  ],
                ),
              ),
              const Sizer(width: 14),
              // Avatar
              _ProfileAvatar(initials: profile.initials),
            ],
          ),

          const Sizer(height: 20),

          // ── Stats row: balance • days left • hours ─────────────────
          Container(
            padding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              vertical: AppSizes.sm + AppSizes.xs,
            ),
            decoration: BoxDecoration(
              color: ColorRes.white.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            ),
            child: Row(
              children: [
                _StatItem(
                  value:
                      profile.walletBalance.toStringAsFixed(0).replaceAllMapped(
                            RegExp(r'\B(?=(\d{3})+(?!\d))'),
                            (m) => ',',
                          ),
                  label: 'ج.م',
                ),
                _VertDivider(),
                _StatItem(
                  value: '${profile.subscriptionDaysRemaining}',
                  label: S.current.daysLeft(profile.subscriptionDaysRemaining),
                ),
                _VertDivider(),
                _StatItem(
                  value: '${profile.totalStudyHours}',
                  label: S.current.hoursStudiedToday,
                ),
              ],
            ),
          ),

          const Sizer(height: 14),

          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                S.current.subscriptionDaysLeft,
                style: tt.bodySmall?.copyWith(
                  color: ColorRes.white.withValues(alpha: 0.7),
                ),
              ),
              Text(
                S.current.subscriptionDaysProgressValue(
                  profile.subscriptionDaysRemaining,
                  profile.subscriptionTotalDays,
                ),
                style: tt.bodySmall?.copyWith(
                  color: ColorRes.anisGold,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
          ),
          const Sizer(height: 6),
          ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusSm),
            child: LinearProgressIndicator(
              value: profile.subscriptionProgress,
              minHeight: 6.h,
              backgroundColor: ColorRes.white.withValues(alpha: 0.18),
              valueColor:
                  const AlwaysStoppedAnimation<Color>(ColorRes.anisGold),
            ),
          ),
        ],
      ),
    );
  }

  String _greeting() {
    final hour = DateTime.now().hour;
    if (hour < 12) return '👋 ${S.current.goodMorning}';
    if (hour < 17) return '👋 ${S.current.goodAfternoon}';
    return '👋 ${S.current.goodEvening}';
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
        horizontal: AppSizes.sm,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGold.withValues(alpha: 0.18),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisGold.withValues(alpha: 0.45),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.star_rounded, size: 12.r, color: ColorRes.anisGold),
          const Sizer(width: 4),
          Text(
            _label(),
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisGold,
                  fontWeight: FontWeight.w700,
                ),
          ),
        ],
      ),
    );
  }
}

// ── Profile avatar ────────────────────────────────────────────────────────────

class _ProfileAvatar extends StatelessWidget {
  final String initials;
  const _ProfileAvatar({required this.initials});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 52.r,
      height: 52.r,
      decoration: BoxDecoration(
        color: ColorRes.anisAvatarDark,
        shape: BoxShape.circle,
        border:
            Border.all(color: ColorRes.white.withValues(alpha: 0.25), width: 2),
      ),
      alignment: Alignment.center,
      child: Text(
        initials,
        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              color: ColorRes.white,
              fontWeight: FontWeight.w700,
            ),
      ),
    );
  }
}

// ── Stat item ─────────────────────────────────────────────────────────────────

class _StatItem extends StatelessWidget {
  final String value;
  final String label;
  const _StatItem({required this.value, required this.label});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Expanded(
      child: Column(
        children: [
          Text(
            value,
            style: tt.headlineSmall?.copyWith(
              color: ColorRes.white,
              fontWeight: FontWeight.w800,
            ),
          ),
          const Sizer(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.65),
            ),
          ),
        ],
      ),
    );
  }
}

// ── Vertical divider between stats ───────────────────────────────────────────

class _VertDivider extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: 1,
      height: 32.h,
      color: ColorRes.white.withValues(alpha: 0.2),
      margin: EdgeInsets.symmetric(horizontal: AppSizes.sm),
    );
  }
}

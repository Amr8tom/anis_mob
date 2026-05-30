import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../../../language/presentation/controller/language_cubit.dart';
import '../../domain/entity/profile_entity.dart';
import '../controller/profile_cubit.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ProfileCubit, ProfileState>(
      builder: (context, state) {
        final isLoading = state.status == ProfileStatus.initial ||
            state.status == ProfileStatus.loading;

        return Scaffold(
          backgroundColor: ColorRes.white,
          body: SafeArea(
            child: RefreshIndicator(
              color: ColorRes.anisGreen,
              onRefresh: () => context.read<ProfileCubit>().refresh(),
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Skeletonizer(
                  enabled: isLoading,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      // ── Green header band ───────────────────────────
                      _ProfileHeader(
                        profile: state.profile,
                        isLoading: isLoading,
                      ),

                      // ── Stats row ───────────────────────────────────
                      _StatsRow(profile: state.profile),

                      const Sizer(height: 8),
                      Container(
                        height: 8,
                        color: ColorRes.anisChipBg,
                      ),
                      const Sizer(height: 8),

                      // ── Badges ──────────────────────────────────────
                      if (!isLoading &&
                          state.profile != null &&
                          state.profile!.badges.isNotEmpty)
                        _BadgesSection(badges: state.profile!.badges),

                      // ── Subscription card ────────────────────────────
                      _SubscriptionCard(profile: state.profile),

                      const Sizer(height: 8),
                      Container(
                        height: 8,
                        color: ColorRes.anisChipBg,
                      ),
                      const Sizer(height: 8),

                      // ── Menu items ──────────────────────────────────
                      _MenuSection(),

                      const Sizer(height: 32),
                    ],
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}

// ─── Profile header ───────────────────────────────────────────────────────────

class _ProfileHeader extends StatelessWidget {
  final ProfileEntity? profile;
  final bool isLoading;
  const _ProfileHeader({required this.profile, required this.isLoading});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final name = profile?.name ?? '████ █████ ████████';
    final uni = profile?.university ?? '████████████';
    final initials = profile?.initials ?? 'م.أ';
    final subType = profile?.subscriptionType ?? 'gold';

    return Container(
      width: double.infinity,
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.md,
        AppSizes.padding,
        AppSizes.xl,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGreen,
        borderRadius: BorderRadius.only(
          bottomLeft: Radius.circular(AppSizes.borderRadiusXXLg),
          bottomRight: Radius.circular(AppSizes.borderRadiusXXLg),
        ),
      ),
      child: Column(
        children: [
          // Avatar
          Container(
            width: 72.r,
            height: 72.r,
            decoration: BoxDecoration(
              color: ColorRes.anisAvatarDark,
              shape: BoxShape.circle,
              border: Border.all(
                color: ColorRes.white.withOpacity(0.3),
                width: 2.5,
              ),
            ),
            alignment: Alignment.center,
            child: Text(
              initials,
              style: tt.headlineSmall?.copyWith(
                color: ColorRes.white,
                fontWeight: FontWeight.w800,
              ),
            ),
          ),

          const Sizer(height: 12),

          // Name
          Text(
            name,
            textAlign: TextAlign.center,
            style: tt.headlineSmall?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.white,
            ),
          ),
          const Sizer(height: 4),

          // University
          Text(
            uni,
            textAlign: TextAlign.center,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.white.withOpacity(0.75),
            ),
          ),
          const Sizer(height: 12),

          // Subscription badge
          _SubscriptionBadge(type: subType),
        ],
      ),
    );
  }
}

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
        horizontal: AppSizes.md,
        vertical: AppSizes.xs + 1,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGold.withOpacity(0.2),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border:
            Border.all(color: ColorRes.anisGold.withOpacity(0.5), width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.star_rounded, size: 14.r, color: ColorRes.anisGold),
          const Sizer(width: 5),
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

// ─── Stats row ────────────────────────────────────────────────────────────────

class _StatsRow extends StatelessWidget {
  final ProfileEntity? profile;
  const _StatsRow({required this.profile});

  @override
  Widget build(BuildContext context) {
    final hours = profile?.totalStudyHours ?? 0;
    final streak = profile?.streakDays ?? 0;
    final sessions = profile?.totalSessions ?? 0;

    return Padding(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.padding,
        vertical: AppSizes.md,
      ),
      child: Row(
        children: [
          _StatCell(
            value: '$hours',
            label: S.current.totalStudyHours,
          ),
          _VertDivider(),
          _StatCell(
            value: '$streak',
            label: S.current.streakDays,
          ),
          _VertDivider(),
          _StatCell(
            value: '$sessions',
            label: S.current.sessionsCount,
          ),
        ],
      ),
    );
  }
}

class _StatCell extends StatelessWidget {
  final String value;
  final String label;
  const _StatCell({required this.value, required this.label});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Expanded(
      child: Column(
        children: [
          Text(
            value,
            style: tt.headlineSmall?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.anisNavy,
            ),
          ),
          const Sizer(height: 2),
          Text(
            label,
            textAlign: TextAlign.center,
            style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
          ),
        ],
      ),
    );
  }
}

class _VertDivider extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: 1,
      height: 36.h,
      color: ColorRes.accent,
    );
  }
}

// ─── Badges section ───────────────────────────────────────────────────────────

class _BadgesSection extends StatelessWidget {
  final List<ProfileBadge> badges;
  const _BadgesSection({required this.badges});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          Text(
            S.current.badgesEarned,
            textAlign: TextAlign.right,
            style: tt.bodyLarge?.copyWith(
              fontWeight: FontWeight.w800,
              color: ColorRes.anisNavy,
            ),
          ),
          const Sizer(height: 12),
          Wrap(
            spacing: AppSizes.sm,
            runSpacing: AppSizes.sm,
            alignment: WrapAlignment.end,
            children: badges
                .map((b) => _BadgeChip(badge: b))
                .toList(),
          ),
          const Sizer(height: 8),
          Container(height: 1, color: ColorRes.accent.withOpacity(0.5)),
        ],
      ),
    );
  }
}

class _BadgeChip extends StatelessWidget {
  final ProfileBadge badge;
  const _BadgeChip({required this.badge});

  IconData _icon() {
    switch (badge.iconKey) {
      case 'streak':
        return Icons.local_fire_department_rounded;
      case 'hours':
        return Icons.timer_rounded;
      case 'top':
        return Icons.emoji_events_rounded;
      default:
        return Icons.star_rounded;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisGreen.withOpacity(0.08),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(
          color: ColorRes.anisGreen.withOpacity(0.2),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(_icon(), size: 14.r, color: ColorRes.anisGreen),
          const Sizer(width: 5),
          Text(
            badge.label,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: ColorRes.anisGreen,
                  fontWeight: FontWeight.w600,
                ),
          ),
        ],
      ),
    );
  }
}

// ─── Subscription card ────────────────────────────────────────────────────────

class _SubscriptionCard extends StatelessWidget {
  final ProfileEntity? profile;
  const _SubscriptionCard({required this.profile});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final subType = profile?.subscriptionType ?? 'free';
    final daysLeft = profile?.subscriptionDaysRemaining ?? 0;
    final balance = profile?.walletBalance ?? 0.0;
    final isGold = subType == 'gold';

    final planLabel = isGold
        ? S.current.goldSubscription
        : subType == 'silver'
            ? S.current.silverSubscription
            : S.current.freeSubscription;

    final planColor = isGold
        ? ColorRes.anisGold
        : subType == 'silver'
            ? ColorRes.anisTextMuted
            : ColorRes.anisGreen;

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.sm,
      ),
      child: Container(
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
          boxShadow: [
            BoxShadow(
              color: ColorRes.anisNavy.withOpacity(0.07),
              blurRadius: 16,
              offset: const Offset(0, 4),
            ),
            BoxShadow(
              color: ColorRes.anisNavy.withOpacity(0.03),
              blurRadius: 4,
              offset: const Offset(0, 1),
            ),
          ],
        ),
        padding: EdgeInsets.all(AppSizes.md + 2),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            // ── Plan name row ─────────────────────────────────
            Row(
              textDirection: TextDirection.rtl,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // Plan badge
                Container(
                  padding: EdgeInsets.symmetric(
                    horizontal: AppSizes.sm + 2,
                    vertical: AppSizes.xs,
                  ),
                  decoration: BoxDecoration(
                    color: planColor.withOpacity(0.1),
                    borderRadius:
                        BorderRadius.circular(AppSizes.borderRadiusMd),
                    border: Border.all(
                      color: planColor.withOpacity(0.35),
                      width: 1,
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.star_rounded, size: 13.r, color: planColor),
                      const Sizer(width: 4),
                      Text(
                        planLabel,
                        style: tt.bodySmall?.copyWith(
                          color: planColor,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ],
                  ),
                ),
                // Current plan label
                Text(
                  S.current.currentPlan,
                  style: tt.bodyMedium?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisNavy,
                  ),
                ),
              ],
            ),

            const Sizer(height: 14),
            Container(height: 1, color: ColorRes.accent.withOpacity(0.5)),
            const Sizer(height: 14),

            // ── Days left + wallet row ─────────────────────────
            Row(
              textDirection: TextDirection.rtl,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                // Wallet balance
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      S.current.walletBalance,
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisHintText,
                      ),
                    ),
                    const Sizer(height: 2),
                    Text(
                      '${balance.toStringAsFixed(0)} ج.م',
                      style: tt.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w800,
                        color: ColorRes.anisNavy,
                      ),
                    ),
                  ],
                ),
                // Days remaining
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      S.current.daysLeft,
                      style: tt.bodySmall?.copyWith(
                        color: ColorRes.anisHintText,
                      ),
                    ),
                    const Sizer(height: 2),
                    Text(
                      '$daysLeft',
                      style: tt.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w800,
                        color: ColorRes.anisNavy,
                      ),
                    ),
                  ],
                ),
              ],
            ),

            const Sizer(height: 16),

            // ── Action button ──────────────────────────────────
            SizedBox(
              width: double.infinity,
              child: isGold
                  ? OutlinedButton(
                      onPressed: () {},
                      style: OutlinedButton.styleFrom(
                        foregroundColor: ColorRes.anisGreen,
                        side: BorderSide(
                          color: ColorRes.anisGreen,
                          width: 1.5,
                        ),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(
                            AppSizes.borderRadiusXLg,
                          ),
                        ),
                        padding: EdgeInsets.symmetric(
                          vertical: AppSizes.sm + 2,
                        ),
                      ),
                      child: Text(
                        S.current.manageSubscription,
                        style: tt.bodyMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: ColorRes.anisGreen,
                        ),
                      ),
                    )
                  : ElevatedButton(
                      onPressed: () {},
                      style: ElevatedButton.styleFrom(
                        backgroundColor: ColorRes.anisGreen,
                        foregroundColor: ColorRes.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(
                            AppSizes.borderRadiusXLg,
                          ),
                        ),
                        padding: EdgeInsets.symmetric(
                          vertical: AppSizes.sm + 2,
                        ),
                      ),
                      child: Text(
                        S.current.upgradePlan,
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
}

// ─── Menu section ─────────────────────────────────────────────────────────────

class _MenuSection extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        children: [
          _MenuTile(
            item: _MenuItem(
              icon: Icons.edit_outlined,
              label: S.current.editProfile,
              onTap: () {},
            ),
          ),
          _MenuTile(
            item: _MenuItem(
              icon: Icons.notifications_outlined,
              label: S.current.notifications,
              onTap: () {},
            ),
          ),
          _MenuTile(
            item: _MenuItem(
              icon: Icons.lock_outline_rounded,
              label: S.current.privacyPolicy,
              onTap: () {},
            ),
          ),
          // ── Change Language ──────────────────────────────────
          BlocBuilder<LanguageCubit, LanguageState>(
            builder: (context, langState) {
              final cubit = context.read<LanguageCubit>();
              final isArabic =
                  cubit.currentLanguage.languageCode == 'ar';
              final switchTo = isArabic ? 'EN' : 'ع';

              return _MenuTile(
                item: _MenuItem(
                  icon: Icons.language_rounded,
                  label: S.current.changeLanguage,
                  onTap: () => cubit.toggleLang(),
                  trailingLabel: switchTo,
                ),
              );
            },
          ),
          _MenuTile(
            item: _MenuItem(
              icon: Icons.logout_rounded,
              label: S.current.logout,
              onTap: () {},
              isDestructive: true,
            ),
          ),
        ],
      ),
    );
  }
}

class _MenuItem {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final bool isDestructive;
  final String? trailingLabel;
  const _MenuItem({
    required this.icon,
    required this.label,
    required this.onTap,
    this.isDestructive = false,
    this.trailingLabel,
  });
}

class _MenuTile extends StatelessWidget {
  final _MenuItem item;
  const _MenuTile({required this.item});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final color = item.isDestructive
        ? ColorRes.anisErrorRed
        : ColorRes.anisTextDark;

    return GestureDetector(
      onTap: item.onTap,
      behavior: HitTestBehavior.opaque,
      child: Padding(
        padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 2),
        child: Row(
          textDirection: TextDirection.rtl,
          children: [
            Container(
              width: 40.r,
              height: 40.r,
              decoration: BoxDecoration(
                color: item.isDestructive
                    ? ColorRes.anisErrorRedBg
                    : ColorRes.anisChipBg,
                borderRadius:
                    BorderRadius.circular(AppSizes.borderRadiusLg),
              ),
              child: Icon(item.icon, size: 20.r, color: color),
            ),
            const Sizer(width: 14),
            Expanded(
              child: Text(
                item.label,
                textAlign: TextAlign.right,
                style: tt.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w600,
                  color: color,
                ),
              ),
            ),
            if (item.trailingLabel != null) ...[
              const Sizer(width: 8),
              Container(
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.sm,
                  vertical: AppSizes.xs,
                ),
                decoration: BoxDecoration(
                  color: ColorRes.anisGreen.withOpacity(0.08),
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusMd),
                  border: Border.all(
                    color: ColorRes.anisGreen.withOpacity(0.25),
                    width: 1,
                  ),
                ),
                child: Text(
                  item.trailingLabel!,
                  style: tt.bodySmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisGreen,
                    fontSize: 11,
                  ),
                ),
              ),
            ] else
              Icon(
                Icons.chevron_left_rounded,
                size: AppSizes.iconSm,
                color: ColorRes.anisHintText,
              ),
          ],
        ),
      ),
    );
  }
}

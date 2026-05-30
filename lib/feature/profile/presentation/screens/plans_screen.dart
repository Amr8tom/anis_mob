import 'package:flutter/material.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';

// ─── Data model ───────────────────────────────────────────────────────────────

class _PlanData {
  final String key; // 'free' | 'silver' | 'gold'
  final String title;
  final String price;
  final Color accentColor;
  final Color bgColor;
  final IconData icon;
  final String? badge;        // "Most Popular" / "Best Value"
  final List<_Feature> features;
  final String ctaLabel;

  const _PlanData({
    required this.key,
    required this.title,
    required this.price,
    required this.accentColor,
    required this.bgColor,
    required this.icon,
    this.badge,
    required this.features,
    required this.ctaLabel,
  });
}

class _Feature {
  final String label;
  final bool included;
  const _Feature(this.label, {this.included = true});
}

// ─── Screen ───────────────────────────────────────────────────────────────────

class PlansScreen extends StatefulWidget {
  final String currentPlan;
  const PlansScreen({super.key, required this.currentPlan});

  @override
  State<PlansScreen> createState() => _PlansScreenState();
}

class _PlansScreenState extends State<PlansScreen> {
  late String _selected;

  @override
  void initState() {
    super.initState();
    _selected = widget.currentPlan;
  }

  List<_PlanData> _plans(BuildContext context) => [
        _PlanData(
          key: 'free',
          title: S.current.planFreeTitle,
          price: S.current.planPriceFree,
          accentColor: ColorRes.anisTextMuted,
          bgColor: ColorRes.anisChipBg,
          icon: Icons.lock_open_rounded,
          features: [
            _Feature(S.current.planFeatureWorkspaces),
            _Feature(S.current.planFeatureSessions2),
            _Feature(S.current.planFeatureAds),
            _Feature(S.current.planFeatureBuddies, included: false),
            _Feature(S.current.planFeatureAnalytics, included: false),
            _Feature(S.current.planFeaturePriority, included: false),
          ],
          ctaLabel: S.current.planCtaFree,
        ),
        _PlanData(
          key: 'silver',
          title: S.current.planSilverTitle,
          price: S.current.planPriceSilver,
          accentColor: ColorRes.silver,
          bgColor: const Color(0xFFF5F5F5),
          icon: Icons.workspace_premium_rounded,
          badge: S.current.planMostPopular,
          features: [
            _Feature(S.current.planFeatureWorkspacesUnlimited),
            _Feature(S.current.planFeatureSessions10),
            _Feature(S.current.planFeatureNoAds),
            _Feature(S.current.planFeatureBuddies),
            _Feature(S.current.planFeatureAnalytics, included: false),
            _Feature(S.current.planFeaturePriority, included: false),
          ],
          ctaLabel: S.current.planCtaSilver,
        ),
        _PlanData(
          key: 'gold',
          title: S.current.planGoldTitle,
          price: S.current.planPriceGold,
          accentColor: ColorRes.anisGold,
          bgColor: const Color(0xFFFFFBF0),
          icon: Icons.emoji_events_rounded,
          badge: S.current.planBestValue,
          features: [
            _Feature(S.current.planFeatureWorkspacesUnlimited),
            _Feature(S.current.planFeatureSessionsUnlimited),
            _Feature(S.current.planFeatureNoAds),
            _Feature(S.current.planFeatureBuddies),
            _Feature(S.current.planFeatureAnalytics),
            _Feature(S.current.planFeaturePriority),
          ],
          ctaLabel: S.current.planCtaGold,
        ),
      ];

  @override
  Widget build(BuildContext context) {
    final plans = _plans(context);
    final selectedPlan = plans.firstWhere((p) => p.key == _selected);

    return Scaffold(
      backgroundColor: ColorRes.anisMintBg,
      appBar: DAppBar(
        showBackArrow: true,
        title: S.current.choosePlanTitle,
      ),
      body: Column(
        children: [
          // ── Tab selector ───────────────────────────────────
          Padding(
            padding: EdgeInsets.fromLTRB(
              AppSizes.padding,
              AppSizes.md,
              AppSizes.padding,
              AppSizes.sm,
            ),
            child: Container(
              padding: EdgeInsets.all(AppSizes.xs),
              decoration: BoxDecoration(
                color: ColorRes.anisLine,
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              ),
              child: Row(
                children: plans.map((plan) {
                  final isSelected = plan.key == _selected;
                  return Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _selected = plan.key),
                      child: AnimatedContainer(
                        duration: const Duration(milliseconds: 200),
                        padding: EdgeInsets.symmetric(vertical: AppSizes.sm),
                        decoration: BoxDecoration(
                          color: isSelected
                              ? ColorRes.white
                              : Colors.transparent,
                          borderRadius: BorderRadius.circular(
                              AppSizes.borderRadiusXLg - AppSizes.xs),
                          boxShadow: isSelected
                              ? [
                                  BoxShadow(
                                    color:
                                        ColorRes.anisNavy.withOpacity(0.08),
                                    blurRadius: AppSizes.sm,
                                    offset: const Offset(0, 2),
                                  )
                                ]
                              : [],
                        ),
                        child: Column(
                          children: [
                            Icon(
                              plan.icon,
                              size: AppSizes.iconSm,
                              color: isSelected
                                  ? plan.accentColor
                                  : ColorRes.anisHintText,
                            ),
                            const Sizer(height: 2),
                            Text(
                              plan.title,
                              style: Theme.of(context)
                                  .textTheme
                                  .bodySmall
                                  ?.copyWith(
                                    fontWeight: isSelected
                                        ? FontWeight.w700
                                        : FontWeight.w500,
                                    color: isSelected
                                        ? plan.accentColor
                                        : ColorRes.anisHintText,
                                  ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ),
          ),

          // ── Plan card (animated) ───────────────────────────
          Expanded(
            child: AnimatedSwitcher(
              duration: const Duration(milliseconds: 250),
              transitionBuilder: (child, animation) => FadeTransition(
                opacity: animation,
                child: SlideTransition(
                  position: Tween<Offset>(
                    begin: const Offset(0, 0.04),
                    end: Offset.zero,
                  ).animate(animation),
                  child: child,
                ),
              ),
              child: _PlanDetailCard(
                key: ValueKey(_selected),
                plan: selectedPlan,
                isCurrent: selectedPlan.key == widget.currentPlan,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ─── Plan Detail Card ─────────────────────────────────────────────────────────

class _PlanDetailCard extends StatelessWidget {
  final _PlanData plan;
  final bool isCurrent;
  const _PlanDetailCard({
    super.key,
    required this.plan,
    required this.isCurrent,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return SingleChildScrollView(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.xl,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // ── Header card ──────────────────────────────────
          Container(
            padding: EdgeInsets.all(AppSizes.md + 4),
            decoration: BoxDecoration(
              color: plan.bgColor,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              border: Border.all(
                color: plan.accentColor.withOpacity(0.3),
                width: 1.5,
              ),
            ),
            child: Column(
              children: [
                // Badge row
                if (plan.badge != null || isCurrent)
                  Padding(
                    padding: EdgeInsets.only(bottom: AppSizes.sm),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        if (plan.badge != null)
                          _TagChip(
                            label: plan.badge!,
                            color: plan.accentColor,
                          ),
                        if (isCurrent) ...[
                          if (plan.badge != null) const Sizer(width: 8),
                          _TagChip(
                            label: S.current.planCurrentBadge,
                            color: ColorRes.anisGreen,
                          ),
                        ],
                      ],
                    ),
                  ),

                // Icon
                Container(
                  width: AppSizes.iconXLarge * 1.5,
                  height: AppSizes.iconXLarge * 1.5,
                  decoration: BoxDecoration(
                    color: plan.accentColor.withOpacity(0.12),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(
                    plan.icon,
                    size: AppSizes.iconLg,
                    color: plan.accentColor,
                  ),
                ),
                const Sizer(height: 12),

                // Plan title
                Text(
                  plan.title,
                  style: tt.headlineMedium?.copyWith(
                    fontWeight: FontWeight.w800,
                    color: ColorRes.anisNavy,
                  ),
                ),
                const Sizer(height: 4),

                // Price
                Text(
                  plan.price,
                  style: tt.headlineSmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: plan.accentColor,
                  ),
                ),
              ],
            ),
          ),

          const Sizer(height: 16),

          // ── Features list ─────────────────────────────────
          Container(
            padding: EdgeInsets.all(AppSizes.md),
            decoration: BoxDecoration(
              color: ColorRes.white,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
              boxShadow: [
                BoxShadow(
                  color: ColorRes.anisNavy.withOpacity(0.05),
                  blurRadius: AppSizes.md,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Column(
              children: plan.features.asMap().entries.map((entry) {
                final isLast = entry.key == plan.features.length - 1;
                return Column(
                  children: [
                    _FeatureRow(
                      feature: entry.value,
                      accentColor: plan.accentColor,
                    ),
                    if (!isLast)
                      Divider(
                        height: AppSizes.md,
                        color: ColorRes.anisLine,
                      ),
                  ],
                );
              }).toList(),
            ),
          ),

          const Sizer(height: 20),

          // ── CTA button ────────────────────────────────────
          if (isCurrent)
            OutlinedButton(
              onPressed: null,
              style: OutlinedButton.styleFrom(
                disabledForegroundColor:
                    plan.accentColor.withOpacity(0.6),
                side: BorderSide(
                    color: plan.accentColor.withOpacity(0.4), width: 1.5),
                shape: RoundedRectangleBorder(
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
              ),
              child: Text(
                S.current.planCurrentBadge,
                style: tt.bodyLarge?.copyWith(fontWeight: FontWeight.w700),
              ),
            )
          else
            ElevatedButton(
              onPressed: () {},
              style: ElevatedButton.styleFrom(
                backgroundColor: plan.accentColor,
                foregroundColor: ColorRes.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusXLg),
                ),
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
              ),
              child: Text(
                plan.ctaLabel,
                style: tt.bodyLarge?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
              ),
            ),
        ],
      ),
    );
  }
}

// ─── Feature Row ──────────────────────────────────────────────────────────────

class _FeatureRow extends StatelessWidget {
  final _Feature feature;
  final Color accentColor;
  const _FeatureRow({required this.feature, required this.accentColor});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: AppSizes.iconSm + 4,
          height: AppSizes.iconSm + 4,
          decoration: BoxDecoration(
            color: feature.included
                ? accentColor.withOpacity(0.1)
                : ColorRes.anisChipBg,
            shape: BoxShape.circle,
          ),
          child: Icon(
            feature.included
                ? Icons.check_rounded
                : Icons.close_rounded,
            size: AppSizes.iconXs,
            color: feature.included ? accentColor : ColorRes.anisHintText,
          ),
        ),
        const Sizer(width: 10),
        Expanded(
          child: Text(
            feature.label,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  color: feature.included
                      ? ColorRes.anisTextDark
                      : ColorRes.anisHintText,
                  fontWeight: feature.included
                      ? FontWeight.w500
                      : FontWeight.w400,
                ),
          ),
        ),
      ],
    );
  }
}

// ─── Tag Chip ─────────────────────────────────────────────────────────────────

class _TagChip extends StatelessWidget {
  final String label;
  final Color color;
  const _TagChip({required this.label, required this.color});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2,
        vertical: AppSizes.xs,
      ),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        border: Border.all(color: color.withOpacity(0.3), width: 1),
      ),
      child: Text(
        label,
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
              color: color,
              fontWeight: FontWeight.w700,
            ),
      ),
    );
  }
}

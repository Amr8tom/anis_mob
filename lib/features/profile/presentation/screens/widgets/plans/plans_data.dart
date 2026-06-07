import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class PlanData {
  final String key; // 'free' | 'silver' | 'gold'
  final String title;
  final String price;
  final Color accentColor;
  final Color bgColor;
  final IconData icon;
  final String? badge;
  final List<PlanFeature> features;
  final String ctaLabel;

  const PlanData({
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

class PlanFeature {
  final String label;
  final bool included;
  const PlanFeature(this.label, {this.included = true});
}

List<PlanData> buildPlans() => [
      PlanData(
        key: 'free',
        title: S.current.planFreeTitle,
        price: S.current.planPriceFree,
        accentColor: ColorRes.anisTextMuted,
        bgColor: ColorRes.anisChipBg,
        icon: Icons.lock_open_rounded,
        features: [
          PlanFeature(S.current.planFeatureWorkspaces),
          PlanFeature(S.current.planFeatureSessions2),
          PlanFeature(S.current.planFeatureAds),
          PlanFeature(S.current.planFeatureBuddies, included: false),
          PlanFeature(S.current.planFeatureAnalytics, included: false),
          PlanFeature(S.current.planFeaturePriority, included: false),
        ],
        ctaLabel: S.current.planCtaFree,
      ),
      PlanData(
        key: 'silver',
        title: S.current.planSilverTitle,
        price: S.current.planPriceSilver,
        accentColor: ColorRes.silver,
        bgColor: const Color(0xFFF5F5F5),
        icon: Icons.workspace_premium_rounded,
        badge: S.current.planMostPopular,
        features: [
          PlanFeature(S.current.planFeatureSilverHours),
          PlanFeature(S.current.planFeatureWorkspacesUnlimited),
          PlanFeature(S.current.planFeatureBuddies),
          PlanFeature(S.current.planFeatureNoAds),
          PlanFeature(S.current.planFeatureAnalytics, included: false),
          PlanFeature(S.current.planFeaturePriority, included: false),
        ],
        ctaLabel: S.current.planCtaSilver,
      ),
      PlanData(
        key: 'gold',
        title: S.current.planGoldTitle,
        price: S.current.planPriceGold,
        accentColor: ColorRes.anisGold,
        bgColor: const Color(0xFFFFFBF0),
        icon: Icons.emoji_events_rounded,
        badge: S.current.planBestValue,
        features: [
          PlanFeature(S.current.planFeatureGoldHours),
          PlanFeature(S.current.planFeatureWorkspacesUnlimited),
          PlanFeature(S.current.planFeatureBuddies),
          PlanFeature(S.current.planFeatureNoAds),
          PlanFeature(S.current.planFeatureAnalytics),
          PlanFeature(S.current.planFeaturePriority),
        ],
        ctaLabel: S.current.planCtaGold,
      ),
    ];

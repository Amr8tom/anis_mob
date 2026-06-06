import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

/// Three small feature-highlight chips shown below the app name on splash.
/// Replaces the old orbiting-pill animation which was too cluttered.
/// RTL-aware: uses Wrap so layout flips with the locale.
class SplashOrbitBubbles extends StatelessWidget {
  /// [angle] kept for API compatibility — no longer used.
  final double angle;
  const SplashOrbitBubbles({super.key, required this.angle});

  @override
  Widget build(BuildContext context) {
    final chips = [
      (icon: '🏢', label: S.current.workspacesTab, color: ColorRes.anisGreen),
      (
        icon: '📅',
        label: S.current.sessionsTab,
        color: ColorRes.anisButtonGreen
      ),
      (icon: '👥', label: S.current.studyBuddy, color: ColorRes.anisGold),
    ];

    return Wrap(
      alignment: WrapAlignment.center,
      spacing: 8,
      runSpacing: 8,
      children: chips
          .map(
              (c) => _FeatureChip(icon: c.icon, label: c.label, color: c.color))
          .toList(),
    );
  }
}

class _FeatureChip extends StatelessWidget {
  final String icon;
  final String label;
  final Color color;
  const _FeatureChip(
      {required this.icon, required this.label, required this.color});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.14),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: color.withValues(alpha: 0.38), width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(icon, style: const TextStyle(fontSize: 13)),
          const SizedBox(width: 5),
          Text(
            label,
            style: Theme.of(context).textTheme.labelSmall?.copyWith(
                  color: ColorRes.white.withValues(alpha: 0.88),
                  fontWeight: FontWeight.w600,
                  fontSize: 12,
                ),
          ),
        ],
      ),
    );
  }
}

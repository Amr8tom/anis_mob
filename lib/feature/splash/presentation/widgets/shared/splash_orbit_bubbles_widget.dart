import 'dart:math' as math;

import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/feature/splash/presentation/widgets/shared/splash_study_bubble_widget.dart';

/// Descriptor for a single orbiting bubble.
class BubbleSpec {
  final String text;
  final Color color;
  final double phase;

  const BubbleSpec(this.text, this.color, this.phase);
}

/// Renders three [SplashStudyBubble]s orbiting an elliptical path.
/// [angle] is the current animation value (0 → 2π), driven by the parent.
class SplashOrbitBubbles extends StatelessWidget {
  final double angle;

  const SplashOrbitBubbles({super.key, required this.angle});

  static const _bubbles = [
    BubbleSpec('📚 Study', ColorRes.anisGreen, 0.0),
    BubbleSpec('🤝 Buddy', ColorRes.anisButtonGreen, 2 * math.pi / 3),
    BubbleSpec('🎓 Focus', ColorRes.anisGold, 4 * math.pi / 3),
  ];

  @override
  Widget build(BuildContext context) {
    const orbitRadius = 54.0;
    const areaSize = (orbitRadius + 46.0) * 2;

    return SizedBox(
      width: areaSize,
      height: areaSize * 0.62,
      child: Stack(
        alignment: Alignment.center,
        children: _bubbles.map((b) {
          final a = angle + b.phase;
          final dx = orbitRadius * math.cos(a);
          final dy = orbitRadius * 0.50 * math.sin(a);
          return Transform.translate(
            offset: Offset(dx, dy),
            child: SplashStudyBubble(text: b.text, color: b.color),
          );
        }).toList(),
      ),
    );
  }
}

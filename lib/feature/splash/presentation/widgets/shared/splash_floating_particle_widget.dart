import 'dart:math' as math;

import 'package:flutter/material.dart';

const _kStudyIcons = [
  '📚', '✏️', '🎓', '📝', '💡', '🔬', '📖', '🏫', '⚗️', '🖊️',
];

/// Immutable data describing a single floating background particle.
class ParticleData {
  final String icon;
  final double leftFraction;
  final double topFraction;
  final double driftFraction;
  final double fontSize;
  final Duration duration;

  ParticleData(math.Random rng, int i)
      : icon = _kStudyIcons[i % _kStudyIcons.length],
        leftFraction = rng.nextDouble() * 0.88,
        topFraction = 0.05 + rng.nextDouble() * 0.78,
        driftFraction = 0.04 + rng.nextDouble() * 0.08,
        fontSize = 18 + rng.nextDouble() * 20,
        duration = Duration(milliseconds: 3500 + rng.nextInt(3000));
}

/// Animates a single study-icon floating vertically and fading in/out.
/// Uses a self-cycling [TweenAnimationBuilder] — StatefulWidget justified
/// purely for the local `_cycle` counter that drives the loop.
class SplashFloatingParticle extends StatefulWidget {
  final ParticleData data;

  const SplashFloatingParticle({super.key, required this.data});

  @override
  State<SplashFloatingParticle> createState() => _SplashFloatingParticleState();
}

class _SplashFloatingParticleState extends State<SplashFloatingParticle> {
  int _cycle = 0;

  @override
  Widget build(BuildContext context) {
    final screenH = MediaQuery.sizeOf(context).height;
    final driftPx = widget.data.driftFraction * screenH;

    return TweenAnimationBuilder<double>(
      key: ValueKey(_cycle),
      tween: Tween(begin: 0.0, end: 1.0),
      duration: widget.data.duration,
      onEnd: () {
        if (mounted) setState(() => _cycle++);
      },
      builder: (_, value, __) {
        final opacity =
            (0.25 + 0.15 * math.sin(value * math.pi)).clamp(0.0, 1.0);
        final dy = (_cycle.isEven ? -1.0 : 1.0) * driftPx * value;
        return Transform.translate(
          offset: Offset(0, dy),
          child: Opacity(
            opacity: opacity,
            child: Text(
              widget.data.icon,
              style: TextStyle(fontSize: widget.data.fontSize),
            ),
          ),
        );
      },
    );
  }
}

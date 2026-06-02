import 'dart:math' as math;

import 'package:flutter/material.dart';

/// Wraps [child] in a gentle sine-wave scale bounce once [active] is true.
/// Uses [TweenAnimationBuilder] with a self-incrementing cycle so it loops
/// indefinitely without needing an external [AnimationController].
class SplashBounceLogo extends StatefulWidget {
  final bool active;
  final Widget child;

  const SplashBounceLogo({
    super.key,
    required this.active,
    required this.child,
  });

  @override
  State<SplashBounceLogo> createState() => _SplashBounceLogoState();
}

class _SplashBounceLogoState extends State<SplashBounceLogo> {
  int _cycle = 0;

  @override
  Widget build(BuildContext context) {
    if (!widget.active) return widget.child;
    return TweenAnimationBuilder<double>(
      key: ValueKey(_cycle),
      tween: Tween(begin: 0.0, end: 1.0),
      duration: const Duration(milliseconds: 2500),
      onEnd: () {
        if (mounted) setState(() => _cycle++);
      },
      builder: (_, value, child) {
        final scale = 1.0 + 0.03 * math.sin(value * math.pi);
        return Transform.scale(scale: scale, child: child);
      },
      child: widget.child,
    );
  }
}

import 'dart:async';

import 'package:flutter/material.dart';

/// Endless, ambient motion for hero/decorative elements: a gentle vertical
/// drift plus an optional "breathing" scale. Like [AnimatedEntrance] it only
/// touches `transform`, so the effect is composited on the GPU and never
/// triggers layout or repaint.
class ContinuousFloat extends StatefulWidget {
  final Widget child;

  /// Duration of one half-cycle (the eased motion auto-reverses).
  final Duration period;

  /// Peak vertical drift in logical px.
  final double translateY;

  /// Peak added scale (e.g. 0.03 → breathes between 1.0 and 1.03).
  final double scaleAmplitude;

  /// Offsets the start so several floats stay out of phase.
  final Duration delay;

  const ContinuousFloat({
    super.key,
    required this.child,
    this.period = const Duration(seconds: 4),
    this.translateY = 6,
    this.scaleAmplitude = 0,
    this.delay = Duration.zero,
  });

  @override
  State<ContinuousFloat> createState() => _ContinuousFloatState();
}

class _ContinuousFloatState extends State<ContinuousFloat>
    with SingleTickerProviderStateMixin {
  late final AnimationController _controller;
  late final Animation<double> _animation;
  Timer? _startTimer;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.period);
    _animation = CurvedAnimation(parent: _controller, curve: Curves.easeInOut);

    if (widget.delay == Duration.zero) {
      _controller.repeat(reverse: true);
    } else {
      _startTimer = Timer(widget.delay, () {
        if (mounted) {
          _controller.repeat(reverse: true);
        }
      });
    }
  }

  @override
  void dispose() {
    _startTimer?.cancel();
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _animation,
      builder: (context, child) {
        // value oscillates 0 → 1 → 0; map to a centred drift and a soft scale.
        final value = _animation.value;
        final dy = (value - 0.5) * 2 * widget.translateY;
        final scale = 1 + value * widget.scaleAmplitude;
        return Transform.translate(
          offset: Offset(0, dy),
          child: widget.scaleAmplitude == 0
              ? child
              : Transform.scale(scale: scale, child: child),
        );
      },
      child: widget.child,
    );
  }
}

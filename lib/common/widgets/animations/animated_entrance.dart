import 'dart:async';

import 'package:flutter/material.dart';

/// A one-shot entrance animation: fade-in combined with a subtle slide-up.
///
/// It animates only GPU-safe, compositor-friendly properties (`opacity` and
/// `transform`) — never layout or paint properties — so a whole column of these
/// stays at 60/120fps. Each instance owns its own controller and starts after
/// [delay], which lets a series of widgets form an orchestrated, staggered
/// cascade simply by handing each one a slightly larger delay.
class AnimatedEntrance extends StatefulWidget {
  final Widget child;

  /// How long to wait before this element starts animating in. Stagger a group
  /// of [AnimatedEntrance]s by increasing this per element.
  final Duration delay;

  final Duration duration;

  /// Vertical distance (logical px) the child travels up into place.
  final double offsetY;

  final Curve curve;

  const AnimatedEntrance({
    super.key,
    required this.child,
    this.delay = Duration.zero,
    this.duration = const Duration(milliseconds: 620),
    this.offsetY = 26,
    this.curve = Curves.easeOutCubic,
  });

  @override
  State<AnimatedEntrance> createState() => _AnimatedEntranceState();
}

class _AnimatedEntranceState extends State<AnimatedEntrance>
    with SingleTickerProviderStateMixin {
  late final AnimationController _controller;
  late final Animation<double> _animation;
  Timer? _startTimer;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration);
    _animation = CurvedAnimation(parent: _controller, curve: widget.curve);

    if (widget.delay == Duration.zero) {
      _controller.forward();
    } else {
      _startTimer = Timer(widget.delay, () {
        if (mounted) {
          _controller.forward();
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
        final value = _animation.value;
        return Opacity(
          opacity: value.clamp(0.0, 1.0),
          child: Transform.translate(
            offset: Offset(0, (1 - value) * widget.offsetY),
            child: child,
          ),
        );
      },
      child: widget.child,
    );
  }
}

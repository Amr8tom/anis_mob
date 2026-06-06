import 'dart:math' as math;

import 'package:flutter/material.dart';

/// Circular progress ring used inside [AttendanceStatCard].
///
/// Draws a soft tinted background track and a colored progress arc on top
/// of it — no external package needed (kept dependency-free to match the
/// project's existing approach).
///
/// [progress] is normalized 0..1.
class AttendanceStatRing extends StatelessWidget {
  const AttendanceStatRing({
    super.key,
    required this.color,
    required this.progress,
    required this.child,
    this.size = 86,
    this.strokeWidth = 6,
  });

  /// Accent color of the foreground arc.
  final Color color;

  /// 0..1 progress value (e.g. `0.7` fills 70% of the ring).
  final double progress;

  /// Centered content (number + unit label).
  final Widget child;

  final double size;
  final double strokeWidth;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: size,
      height: size,

      /// Animate the arc from 0 → target progress on first build so the
      /// dashboard feels alive when the user lands on the home screen.
      /// `TweenAnimationBuilder` naturally re-animates whenever the
      /// `end` value changes, so refreshed data smoothly transitions too.
      child: TweenAnimationBuilder<double>(
        duration: const Duration(milliseconds: 800),
        curve: Curves.easeOutCubic,
        tween: Tween<double>(
          begin: 0,
          end: progress.clamp(0.0, 1.0),
        ),
        builder: (context, animatedProgress, _) {
          return CustomPaint(
            painter: _RingPainter(
              color: color,
              progress: animatedProgress,
              strokeWidth: strokeWidth,
            ),
            child: Center(child: child),
          );
        },
      ),
    );
  }
}

class _RingPainter extends CustomPainter {
  _RingPainter({
    required this.color,
    required this.progress,
    required this.strokeWidth,
  });

  final Color color;
  final double progress;
  final double strokeWidth;

  @override
  void paint(Canvas canvas, Size size) {
    final center = Offset(size.width / 2, size.height / 2);
    final radius = (math.min(size.width, size.height) - strokeWidth) / 2;

    /// Background track — very faint tint of the same color.
    final trackPaint = Paint()
      ..style = PaintingStyle.stroke
      ..strokeWidth = strokeWidth
      ..strokeCap = StrokeCap.round
      ..color = color.withValues(alpha: 0.15);
    canvas.drawCircle(center, radius, trackPaint);

    /// Foreground arc — starts at the top (-90°) and sweeps clockwise.
    final arcPaint = Paint()
      ..style = PaintingStyle.stroke
      ..strokeWidth = strokeWidth
      ..strokeCap = StrokeCap.round
      ..color = color;

    final sweep = 2 * math.pi * progress;
    canvas.drawArc(
      Rect.fromCircle(center: center, radius: radius),
      -math.pi / 2,
      sweep,
      false,
      arcPaint,
    );
  }

  @override
  bool shouldRepaint(covariant _RingPainter old) {
    return old.progress != progress ||
        old.color != color ||
        old.strokeWidth != strokeWidth;
  }
}

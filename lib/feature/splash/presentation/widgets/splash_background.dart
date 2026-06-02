import 'dart:math' as math;

import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';
import 'shared/splash_floating_particle_widget.dart';

/// Animated dark-green background with aurora blobs + floating study icons.
class SplashBackground extends StatefulWidget {
  final Widget child;

  const SplashBackground({super.key, required this.child});

  @override
  State<SplashBackground> createState() => _SplashBackgroundState();
}

class _SplashBackgroundState extends State<SplashBackground>
    with SingleTickerProviderStateMixin {
  late final AnimationController _blobCtrl;
  late final List<ParticleData> _particles;

  @override
  void initState() {
    super.initState();
    _blobCtrl = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 8),
    )..repeat(reverse: true);

    final rng = math.Random(42);
    _particles = List.generate(10, (i) => ParticleData(rng, i));
  }

  @override
  void dispose() {
    _blobCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.sizeOf(context);
    return Stack(
      children: [
        // 1 — Dark gradient
        Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [
                ColorRes.anisAuthBgTop,
                ColorRes.anisAuthBgMid,
                ColorRes.anisAuthBgBottom,
              ],
            ),
          ),
        ),

        // 2 — Aurora blobs
        AnimatedBuilder(
          animation: _blobCtrl,
          builder: (_, __) => CustomPaint(
            painter: _BlobPainter(_blobCtrl.value),
            child: const SizedBox.expand(),
          ),
        ),

        // 3 — Floating study particles
        ..._particles.map(
          (p) => Positioned(
            left: p.leftFraction * size.width,
            top: p.topFraction * size.height,
            child: SplashFloatingParticle(data: p),
          ),
        ),

        // 4 — Page content
        widget.child,
      ],
    );
  }
}

// ─── Aurora blob painter ──────────────────────────────────────────────────────
// Kept private — it is an internal rendering detail of SplashBackground,
// not a reusable UI building block.

class _BlobPainter extends CustomPainter {
  final double t;
  const _BlobPainter(this.t);

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 60);

    // Green blob — upper-right
    paint.color = ColorRes.anisGreen.withValues(alpha: 0.20);
    canvas.drawCircle(
      Offset(size.width * (0.72 + t * 0.12), size.height * 0.16),
      size.width * 0.30,
      paint,
    );

    // Mint blob — centre
    paint.color = ColorRes.anisButtonGreen.withValues(alpha: 0.12);
    canvas.drawCircle(
      Offset(size.width * (0.28 - t * 0.10), size.height * 0.52),
      size.width * 0.32,
      paint,
    );

    // Navy blob — lower
    paint.color = ColorRes.anisNavy.withValues(alpha: 0.40);
    canvas.drawCircle(
      Offset(
        size.width * (0.55 + t * 0.10),
        size.height * (0.74 + t * 0.06),
      ),
      size.width * 0.28,
      paint,
    );
  }

  @override
  bool shouldRepaint(_BlobPainter old) => old.t != t;
}

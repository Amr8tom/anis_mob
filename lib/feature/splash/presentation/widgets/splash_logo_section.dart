import 'dart:math' as math;

import 'package:flutter/material.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';
import 'shared/splash_bounce_logo_widget.dart';
import 'shared/splash_logo_circle_widget.dart';
import 'shared/splash_orbit_bubbles_widget.dart';

/// Splash centre section: animated logo, app name, typewriter tagline,
/// and orbiting study-bubble mascot.
class SplashLogoSection extends StatefulWidget {
  const SplashLogoSection({super.key});

  @override
  State<SplashLogoSection> createState() => _SplashLogoSectionState();
}

class _SplashLogoSectionState extends State<SplashLogoSection>
    with TickerProviderStateMixin {
  late final AnimationController _entrance;
  late final AnimationController _orbit;

  late final Animation<double> _logoFade;
  late final Animation<double> _logoScale;
  late final Animation<Offset> _logoSlide;
  late final Animation<double> _taglineFade;

  bool _entranceDone = false;
  String _typewriterText = '';

  @override
  void initState() {
    super.initState();

    _entrance = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1800),
    );
    _orbit = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 6),
    );

    _logoFade = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.60, curve: Curves.easeIn),
      ),
    );
    _logoScale = Tween<double>(begin: 0.3, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.65, curve: Curves.easeOutBack),
      ),
    );
    _logoSlide = Tween<Offset>(
      begin: const Offset(0.0, 0.6),
      end: Offset.zero,
    ).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.60, curve: Curves.easeOutCubic),
      ),
    );
    _taglineFade = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.55, 0.85, curve: Curves.easeIn),
      ),
    );

    _entrance.forward().then((_) {
      if (!mounted) return;
      setState(() => _entranceDone = true);
      _orbit.repeat();
      _runTypewriter();
    });
  }

  Future<void> _runTypewriter() async {
    if (!mounted) return;
    final full = S.current.splashTagline;
    for (var i = 1; i <= full.length; i++) {
      await Future.delayed(const Duration(milliseconds: 55));
      if (!mounted) return;
      setState(() => _typewriterText = full.substring(0, i));
    }
  }

  @override
  void dispose() {
    _entrance.dispose();
    _orbit.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        // ── Logo with idle bounce ─────────────────────────────────────────
        SplashBounceLogo(
          active: _entranceDone,
          child: FadeTransition(
            opacity: _logoFade,
            child: SlideTransition(
              position: _logoSlide,
              child: ScaleTransition(
                scale: _logoScale,
                child: const SplashLogoCircle(),
              ),
            ),
          ),
        ),

        const SizedBox(height: 20),

        // ── App name ──────────────────────────────────────────────────────
        FadeTransition(
          opacity: _logoFade,
          child: Text(
            'Anis',
            style: Theme.of(context).textTheme.displayMedium?.copyWith(
              color: ColorRes.white,
              fontWeight: FontWeight.w900,
              letterSpacing: 2,
            ),
          ),
        ),

        const SizedBox(height: 8),

        // ── Typewriter tagline ────────────────────────────────────────────
        FadeTransition(
          opacity: _taglineFade,
          child: Text(
            _typewriterText.isEmpty ? ' ' : _typewriterText,
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              fontSize: AppSizes.fontSizeSm,
              color: ColorRes.white.withValues(alpha: 0.60),
            ),
            textAlign: TextAlign.center,
          ),
        ),

        const SizedBox(height: 24),

        // ── Orbiting study-bubble mascot ──────────────────────────────────
        AnimatedOpacity(
          opacity: _entranceDone ? 1.0 : 0.0,
          duration: const Duration(milliseconds: 500),
          child: AnimatedBuilder(
            animation: _orbit,
            builder: (_, __) => SplashOrbitBubbles(
              angle: _orbit.value * 2 * math.pi,
            ),
          ),
        ),
      ],
    );
  }
}

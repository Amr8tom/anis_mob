import 'dart:math' as math;

import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';
import 'shared/splash_bounce_logo_widget.dart';
import 'shared/splash_logo_circle_widget.dart';
import 'shared/splash_orbit_bubbles_widget.dart';

/// Splash centre section: animated logo, app name, typewriter tagline,
/// orbiting study-bubble mascot, and a bottom entrance fade.
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
  late final Animation<double> _nameFade;
  late final Animation<Offset> _nameSlide;

  bool _entranceDone = false;
  String _typewriterText = '';

  @override
  void initState() {
    super.initState();

    _entrance = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1600),
    );
    _orbit = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 6),
    );

    _logoFade = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.55, curve: Curves.easeOut),
      ),
    );
    _logoScale = Tween<double>(begin: 0.25, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.60, curve: Curves.easeOutBack),
      ),
    );
    _logoSlide = Tween<Offset>(
      begin: const Offset(0.0, 0.5),
      end: Offset.zero,
    ).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.0, 0.55, curve: Curves.easeOutCubic),
      ),
    );
    _nameFade = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.38, 0.75, curve: Curves.easeOut),
      ),
    );
    _nameSlide = Tween<Offset>(
      begin: const Offset(0.0, 0.3),
      end: Offset.zero,
    ).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.38, 0.72, curve: Curves.easeOutCubic),
      ),
    );
    _taglineFade = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _entrance,
        curve: const Interval(0.60, 0.90, curve: Curves.easeOut),
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
      await Future.delayed(const Duration(milliseconds: 48));
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
    final tt = Theme.of(context).textTheme;

    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        // ── Logo + entrance animation ─────────────────────────────────────
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

        const Sizer(height: 20),

        // ── App name ──────────────────────────────────────────────────────
        FadeTransition(
          opacity: _nameFade,
          child: SlideTransition(
            position: _nameSlide,
            child: Text(
              S.current.splashTitle,
              style: tt.displaySmall?.copyWith(
                color: ColorRes.white,
                fontWeight: FontWeight.w900,
                letterSpacing: 1.5,
              ),
            ),
          ),
        ),

        const Sizer(height: 6),

        // ── Typewriter tagline ────────────────────────────────────────────
        FadeTransition(
          opacity: _taglineFade,
          child: Text(
            _typewriterText.isEmpty ? ' ' : _typewriterText,
            style: tt.bodyMedium?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.58),
              letterSpacing: 0.3,
            ),
            textAlign: TextAlign.center,
          ),
        ),

        const Sizer(height: 28),

        // ── Orbiting study-bubble mascot ──────────────────────────────────
        AnimatedOpacity(
          opacity: _entranceDone ? 1.0 : 0.0,
          duration: const Duration(milliseconds: 600),
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

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:anis/core/constants/app_sizes.dart';

class SplashLanguageCard extends StatefulWidget {
  final String flag;
  final String languageName;
  final String nativeHint;
  final List<Color> gradColors;
  final Color glowColor;
  final VoidCallback onTap;

  const SplashLanguageCard({
    super.key,
    required this.flag,
    required this.languageName,
    required this.nativeHint,
    required this.gradColors,
    required this.glowColor,
    required this.onTap,
  });

  @override
  State<SplashLanguageCard> createState() => _SplashLanguageCardState();
}

class _SplashLanguageCardState extends State<SplashLanguageCard> {
  bool _pressed = false;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTapDown: (_) => setState(() => _pressed = true),
      onTapUp: (_) {
        setState(() => _pressed = false);
        HapticFeedback.lightImpact();
        widget.onTap();
      },
      onTapCancel: () => setState(() => _pressed = false),
      child: AnimatedScale(
        scale: _pressed ? 1.04 : 1.0,
        duration: const Duration(milliseconds: 120),
        curve: Curves.easeOut,
        child: Container(
          height: AppSizes.containerMedium,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(24),
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: widget.gradColors,
            ),
            border: Border.all(
              color: Colors.white.withValues(alpha: 0.12),
              width: 1.0,
            ),
            boxShadow: [
              BoxShadow(
                color: widget.glowColor.withValues(alpha: 0.25),
                blurRadius: 16,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(widget.flag, style: const TextStyle(fontSize: 30)),
              const SizedBox(height: 6),
              Text(
                widget.languageName,
                style: TextStyle(
                  fontFamily: 'Cairo',
                  fontSize: AppSizes.fontSizeLg,
                  fontWeight: FontWeight.w800,
                  color: Colors.white,
                ),
              ),
              Text(
                widget.nativeHint,
                style: TextStyle(
                  fontFamily: 'Cairo',
                  fontSize: 12,
                  fontWeight: FontWeight.w400,
                  color: Colors.white.withValues(alpha: 0.55),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

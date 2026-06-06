import 'package:flutter/material.dart';
import 'package:anis/core/constants/asset_resoures.dart';
import 'package:anis/core/constants/colors.dart';

class SplashLogoCircle extends StatelessWidget {
  const SplashLogoCircle({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 120,
      height: 120,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        gradient: const LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [ColorRes.anisGreen, ColorRes.anisButtonGreen],
        ),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisGreen.withValues(alpha: 0.65),
            blurRadius: 40,
            spreadRadius: 6,
          ),
          BoxShadow(
            color: ColorRes.anisButtonGreen.withValues(alpha: 0.25),
            blurRadius: 20,
            spreadRadius: 2,
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Image.asset(
          AssetRes.appIcon,
          fit: BoxFit.contain,
          // Flutter-drawn fallback when the asset file is missing/broken
          errorBuilder: (_, __, ___) => const Center(
            child: Text(
              'أ',
              style: TextStyle(
                color: ColorRes.white,
                fontSize: 54,
                fontWeight: FontWeight.w900,
                height: 1,
              ),
            ),
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import '../models/onboarding_model.dart';
import 'onboarding_illustration_widget.dart';

class OnboardingPageWidget extends StatelessWidget {
  final OnboardingModel page;
  final int index;

  const OnboardingPageWidget({
    super.key,
    required this.page,
    required this.index,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final size = MediaQuery.sizeOf(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // ── Illustrated top section ───────────────────────────
        SizedBox(
          height: size.height * 0.46,
          child: Stack(
            children: [
              // Mint gradient background
              Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      ColorRes.anisMintBg,
                      ColorRes.white.withValues(alpha: 0.0),
                    ],
                  ),
                ),
              ),
              // Decorative corner blobs
              Positioned(
                top: -30,
                right: -30,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: ColorRes.anisGreen.withValues(alpha: 0.08),
                  ),
                ),
              ),
              Positioned(
                bottom: -20,
                left: -20,
                child: Container(
                  width: 90,
                  height: 90,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: ColorRes.anisGold.withValues(alpha: 0.09),
                  ),
                ),
              ),
              // Illustration
              Padding(
                padding: EdgeInsets.fromLTRB(
                  AppSizes.xl,
                  AppSizes.xl,
                  AppSizes.xl,
                  AppSizes.sm,
                ),
                child: OnboardingIllustration(pageIndex: index),
              ),
            ],
          ),
        ),

        // ── Content section (no Expanded — natural height) ────
        Container(
          color: ColorRes.white,
          padding: EdgeInsets.fromLTRB(
            AppSizes.xl,
            AppSizes.md,
            AppSizes.xl,
            AppSizes.sm,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              // Green accent bar
              Container(
                width: 32,
                height: 4,
                decoration: BoxDecoration(
                  color: ColorRes.anisGreen,
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusXXLg),
                ),
              ),
              const Sizer(height: 14),
              // Title
              Text(
                page.title,
                style: tt.headlineSmall?.copyWith(
                  color: ColorRes.anisNavy,
                  fontWeight: FontWeight.w800,
                  height: 1.22,
                  fontSize: 22,
                ),
              ),
              const Sizer(height: 10),
              // Description
              Text(
                page.description,
                maxLines: 4,
                style: tt.bodyMedium?.copyWith(
                  color: ColorRes.anisTextMuted,
                  height: 1.65,
                  fontSize: 14,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

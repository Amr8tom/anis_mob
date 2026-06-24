import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import '../models/onboarding_model.dart';
import 'onboarding_illustration_widget.dart';

class OnboardingPageWidget extends StatefulWidget {
  final OnboardingModel page;
  final int index;

  const OnboardingPageWidget({
    super.key,
    required this.page,
    required this.index,
  });

  @override
  State<OnboardingPageWidget> createState() => _OnboardingPageWidgetState();
}

class _OnboardingPageWidgetState extends State<OnboardingPageWidget>
    with SingleTickerProviderStateMixin {
  late final AnimationController _blobController;

  @override
  void initState() {
    super.initState();
    _blobController = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 4),
    )..repeat(reverse: true);
  }

  @override
  void dispose() {
    _blobController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final size = MediaQuery.sizeOf(context);

    return Stack(
      children: [
        // ── Animated Decorative Blobs ───────────────────────────
        Positioned(
          top: size.height * 0.05,
          right: -40,
          child: AnimatedBuilder(
            animation: _blobController,
            builder: (context, child) {
              return Transform.translate(
                offset: Offset(0, 15 * _blobController.value),
                child: child,
              );
            },
            child: Container(
              width: 150,
              height: 150,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: ColorRes.anisGreen.withValues(alpha: 0.06),
              ),
            ),
          ),
        ),
        Positioned(
          top: size.height * 0.25,
          left: -30,
          child: AnimatedBuilder(
            animation: _blobController,
            builder: (context, child) {
              return Transform.translate(
                offset: Offset(0, -20 * _blobController.value),
                child: child,
              );
            },
            child: Container(
              width: 120,
              height: 120,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: ColorRes.anisGold.withValues(alpha: 0.08),
              ),
            ),
          ),
        ),

        // ── Main Content ───────────────────────────────────────
        Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Illustration Area
            SizedBox(
              height: size.height * 0.48,
              child: Padding(
                padding: EdgeInsets.fromLTRB(
                  AppSizes.xl,
                  AppSizes.xxl,
                  AppSizes.xl,
                  AppSizes.sm,
                ),
                child: OnboardingIllustration(pageIndex: widget.index),
              ),
            ),

            // Floating Text Card with Entry Animation
            Expanded(
              child: TweenAnimationBuilder<double>(
                key: ValueKey('page_${widget.index}'),
                tween: Tween<double>(begin: 0.0, end: 1.0),
                duration: const Duration(milliseconds: 600),
                curve: Curves.easeOutCubic,
                builder: (context, value, child) {
                  return Transform.translate(
                    offset: Offset(0, 40 * (1 - value)),
                    child: Opacity(
                      opacity: value,
                      child: child,
                    ),
                  );
                },
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: AppSizes.xl),
                  child: Align(
                    alignment: Alignment.topCenter,
                    child: Container(
                      margin: const EdgeInsets.only(top: 10),
                      padding: EdgeInsets.all(AppSizes.xl),
                      decoration: BoxDecoration(
                        color: ColorRes.white,
                        borderRadius: BorderRadius.circular(32),
                        boxShadow: [
                          BoxShadow(
                            color: ColorRes.anisNavy.withValues(alpha: 0.04),
                            blurRadius: 24,
                            offset: const Offset(0, 12),
                          ),
                        ],
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        mainAxisSize: MainAxisSize.min,
                        children: [

                          const Sizer(height: 24),
                          // Title
                          Text(
                            widget.page.title,
                            textAlign: TextAlign.center,
                            style: tt.headlineSmall?.copyWith(
                              color: ColorRes.anisNavy,
                              fontWeight: FontWeight.w800,
                              height: 1.3,
                              fontSize: 24,
                            ),
                          ),
                          const Sizer(height: 16),
                          // Description
                          Text(
                            widget.page.description,
                            textAlign: TextAlign.center,
                            maxLines: 4,
                            style: tt.bodyMedium?.copyWith(
                              color: ColorRes.anisTextMuted,
                              height: 1.6,
                              fontSize: 15,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }
}

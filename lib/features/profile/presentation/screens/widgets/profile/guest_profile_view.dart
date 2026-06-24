import 'dart:ui';
import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../core/extentions/navigation_extension.dart';
import '../../../../../../core/routing/route_names.dart';
import '../../../../../../generated/l10n.dart';

class GuestProfileView extends StatefulWidget {
  const GuestProfileView({super.key});

  @override
  State<GuestProfileView> createState() => _GuestProfileViewState();
}

class _GuestProfileViewState extends State<GuestProfileView> with SingleTickerProviderStateMixin {
  late final AnimationController _controller;
  late final Animation<double> _slideAnimation;
  late final Animation<double> _fadeAnimation;
  late final Animation<double> _floatAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 1200),
    );

    _fadeAnimation = CurvedAnimation(
      parent: _controller,
      curve: const Interval(0.0, 0.6, curve: Curves.easeOutCubic),
    );

    _slideAnimation = Tween<double>(begin: 40, end: 0).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.0, 0.6, curve: Curves.easeOutCubic),
      ),
    );

    _floatAnimation = Tween<double>(begin: -6, end: 6).animate(
      CurvedAnimation(
        parent: _controller,
        curve: const Interval(0.4, 1.0, curve: Curves.easeInOutSine),
      ),
    );

    _controller.forward();
    // Start repeating the floating animation after the entrance finishes
    _controller.addStatusListener((status) {
      if (status == AnimationStatus.completed) {
        _controller.reverse();
      } else if (status == AnimationStatus.dismissed) {
        _controller.forward();
      }
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: ColorRes.anisMintBg,
      body: Stack(
        children: [
          // ── Decorative Background Blobs ──────────────────────
          Positioned(
            top: -50,
            right: -80,
            child: _buildBlurryBlob(ColorRes.anisGreen.withValues(alpha: 0.15), 250),
          ),
          Positioned(
            bottom: -100,
            left: -80,
            child: _buildBlurryBlob(ColorRes.anisGold.withValues(alpha: 0.15), 300),
          ),

          // ── Main Content ──────────────────────────────────────
          SafeArea(
            child: Center(
              child: Padding(
                padding: EdgeInsets.symmetric(horizontal: AppSizes.xl),
                child: AnimatedBuilder(
                  animation: _controller,
                  builder: (context, child) {
                    // For the initial slide-in, we just use _slideAnimation and _fadeAnimation
                    // But once it completes, we want only the _floatAnimation to affect the card's Y position.
                    // A simple trick: use _slideAnimation while _controller.value < 0.6, and _floatAnimation thereafter.
                    // But since the controller is reversing, the slide will reverse too!
                    // Let's just use a simpler approach: the card doesn't float, only the icon floats.
                    return Transform.translate(
                      offset: Offset(0, _slideAnimation.value),
                      child: Opacity(
                        opacity: _fadeAnimation.value,
                        child: child,
                      ),
                    );
                  },
                  child: Container(
                    padding: EdgeInsets.all(AppSizes.xl),
                    decoration: BoxDecoration(
                      color: ColorRes.white,
                      borderRadius: BorderRadius.circular(32),
                      boxShadow: [
                        BoxShadow(
                          color: ColorRes.anisNavy.withValues(alpha: 0.05),
                          blurRadius: 50,
                          offset: const Offset(0, 15),
                        ),
                        BoxShadow(
                          color: ColorRes.anisGreen.withValues(alpha: 0.03),
                          blurRadius: 20,
                          spreadRadius: 10,
                        ),
                      ],
                    ),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        // Floating user icon
                        AnimatedBuilder(
                          animation: _floatAnimation,
                          builder: (context, child) {
                            return Transform.translate(
                              offset: Offset(0, _floatAnimation.value),
                              child: child,
                            );
                          },
                          child: Container(
                            width: 100,
                            height: 100,
                            decoration: BoxDecoration(
                              color: ColorRes.white,
                              shape: BoxShape.circle,
                              boxShadow: [
                                BoxShadow(
                                  color: ColorRes.anisGreen.withValues(alpha: 0.2),
                                  blurRadius: 30,
                                  spreadRadius: 5,
                                  offset: const Offset(0, 10),
                                ),
                              ],
                            ),
                            child: Center(
                              child: Container(
                                width: 76,
                                height: 76,
                                decoration: const BoxDecoration(
                                  color: ColorRes.anisTagGreen,
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(
                                  Icons.person_outline_rounded,
                                  color: ColorRes.anisGreen,
                                  size: 38,
                                ),
                              ),
                            ),
                          ),
                        ),
                        
                        const Sizer(height: 32),
                        
                        Text(
                          S.current.signInToSeeProfile,
                          textAlign: TextAlign.center,
                          style: tt.headlineSmall?.copyWith(
                            color: ColorRes.anisNavy,
                            fontWeight: FontWeight.w800,
                            height: 1.3,
                            letterSpacing: -0.5,
                          ),
                        ),
                        const Sizer(height: 14),
                        
                        Text(
                          S.current.signInToSeeProfileSubtitle,
                          textAlign: TextAlign.center,
                          style: tt.bodyMedium?.copyWith(
                            color: ColorRes.anisTextMuted,
                            height: 1.6,
                            fontSize: 14,
                          ),
                        ),
                        const Sizer(height: 40),
                        
                        // Premium button
                        Container(
                          width: double.infinity,
                          decoration: BoxDecoration(
                            boxShadow: [
                              BoxShadow(
                                color: ColorRes.anisGreen.withValues(alpha: 0.35),
                                blurRadius: 24,
                                offset: const Offset(0, 8),
                              ),
                            ],
                          ),
                          child: ElevatedButton(
                            onPressed: () => context.pushReplacementNamed(DRoutesName.loginRoute),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: ColorRes.anisGreen,
                              foregroundColor: ColorRes.white,
                              elevation: 0,
                              padding: const EdgeInsets.symmetric(vertical: 18),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(20),
                              ),
                            ),
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                const Icon(Icons.login_rounded, size: 22),
                                const SizedBox(width: 8),
                                Text(
                                  S.current.signIn,
                                  style: const TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.w700,
                                  ),
                                ),
                              ],
                            ),
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
    );
  }

  Widget _buildBlurryBlob(Color color, double size) {
    return ImageFiltered(
      imageFilter: ImageFilter.blur(sigmaX: 50, sigmaY: 50),
      child: Container(
        width: size,
        height: size,
        decoration: BoxDecoration(
          color: color,
          shape: BoxShape.circle,
        ),
      ),
    );
  }
}

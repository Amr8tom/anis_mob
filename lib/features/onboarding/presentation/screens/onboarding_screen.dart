import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/routing/route_names.dart';
import 'package:anis/core/utils/enums/general_status.dart';
import 'package:anis/generated/l10n.dart';

import '../controller/onboarding_cubit.dart';
import '../models/onboarding_model.dart';
import '../widgets/onboarding_page_widget.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  @override
  void initState() {
    super.initState();
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  void _onPageChanged(int page) => setState(() => _currentPage = page);

  void _finish() => context.read<OnboardingCubit>().completeOnboarding();

  void _next() {
    final pages = OnboardingData.getPages(context);
    if (_currentPage < pages.length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 380),
        curve: Curves.easeInOutCubic,
      );
    } else {
      _finish();
    }
  }

  bool get _isLast =>
      _currentPage == OnboardingData.getPages(context).length - 1;

  @override
  Widget build(BuildContext context) {
    final pages = OnboardingData.getPages(context);
    final tt = Theme.of(context).textTheme;

    return BlocListener<OnboardingCubit, OnboardingState>(
      listenWhen: (prev, curr) => prev.status != curr.status,
      listener: (_, state) {
        if (state.status.isSuccess) {
          context.pushReplacementNamed(DRoutesName.loginRoute);
        }
      },
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        body: Stack(
          children: [
            // ── Page content ──────────────────────────────────
            Column(
              children: [
                Expanded(
                  child: PageView.builder(
                    controller: _pageController,
                    onPageChanged: _onPageChanged,
                    itemCount: pages.length,
                    itemBuilder: (_, i) => OnboardingPageWidget(
                      page: pages[i],
                      index: i,
                    ),
                  ),
                ),

                // ── Bottom controls ───────────────────────────
                Container(
                  padding: EdgeInsets.fromLTRB(
                    AppSizes.xl,
                    AppSizes.sm,
                    AppSizes.xl,
                    AppSizes.ld +
                        24, // extra bottom padding for safe area since we removed SafeArea
                  ),
                  child: Column(
                    children: [
                      // Dot indicators
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: List.generate(pages.length, (i) {
                          final active = i == _currentPage;
                          return AnimatedContainer(
                            duration: const Duration(milliseconds: 300),
                            curve: Curves.easeOutCubic,
                            margin: const EdgeInsets.symmetric(horizontal: 4),
                            width: active ? 32 : 8,
                            height: 8,
                            decoration: BoxDecoration(
                              color: active
                                  ? ColorRes.anisGreen
                                  : ColorRes.anisGreen.withValues(alpha: 0.2),
                              borderRadius: BorderRadius.circular(
                                AppSizes.borderRadiusXXLg,
                              ),
                              boxShadow: active
                                  ? [
                                      BoxShadow(
                                        color: ColorRes.anisGreen
                                            .withValues(alpha: 0.4),
                                        blurRadius: 8,
                                        offset: const Offset(0, 2),
                                      ),
                                    ]
                                  : null,
                            ),
                          );
                        }),
                      ),

                      const Sizer(height: 32),

                      // Continue / Get Started button
                      BlocBuilder<OnboardingCubit, OnboardingState>(
                        buildWhen: (p, c) => p.status != c.status,
                        builder: (_, state) {
                          final loading = state.status.isLoading;
                          return Container(
                            width: double.infinity,
                            height: 56, // Slightly taller button
                            decoration: BoxDecoration(
                              boxShadow: [
                                BoxShadow(
                                  color: ColorRes.anisGreen
                                      .withValues(alpha: 0.25),
                                  blurRadius: 16,
                                  offset: const Offset(0, 8),
                                ),
                              ],
                            ),
                            child: ElevatedButton(
                              onPressed: loading ? null : _next,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: ColorRes.anisGreen,
                                foregroundColor: ColorRes.white,
                                disabledBackgroundColor:
                                    ColorRes.anisGreen.withValues(alpha: 0.55),
                                elevation: 0,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(28),
                                ),
                              ),
                              child: loading
                                  ? const SizedBox(
                                      width: 22,
                                      height: 22,
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2.5,
                                        valueColor:
                                            AlwaysStoppedAnimation<Color>(
                                          ColorRes.white,
                                        ),
                                      ),
                                    )
                                  : Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      children: [
                                        Text(
                                          _isLast
                                              ? S.current.getStarted
                                              : S.current.continuee,
                                          style: tt.titleSmall?.copyWith(
                                            fontWeight: FontWeight.w700,
                                            color: ColorRes.white,
                                            fontSize: 16,
                                            letterSpacing: 0.5,
                                          ),
                                        ),
                                        if (!_isLast) ...[
                                          const SizedBox(width: 8),
                                          const Icon(
                                            Icons.arrow_forward_rounded,
                                            size: 20,
                                            color: ColorRes.white,
                                          ),
                                        ],
                                        if (_isLast) ...[
                                          const SizedBox(width: 8),
                                          const Icon(
                                            Icons.check_circle_outline_rounded,
                                            size: 20,
                                            color: ColorRes.white,
                                          ),
                                        ],
                                      ],
                                    ),
                            ),
                          );
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),

            // ── Skip button (top trailing) ────────────────────
            SafeArea(
              child: Align(
                alignment: AlignmentDirectional.topEnd,
                child: Padding(
                  padding: EdgeInsets.symmetric(
                      horizontal: AppSizes.md, vertical: AppSizes.sm),
                  child: AnimatedOpacity(
                    opacity: _isLast ? 0.0 : 1.0,
                    duration: const Duration(milliseconds: 250),
                    child: IgnorePointer(
                      ignoring: _isLast,
                      child: TextButton(
                        onPressed: _finish,
                        style: TextButton.styleFrom(
                          backgroundColor:
                              ColorRes.white.withValues(alpha: 0.6),
                          padding: const EdgeInsets.symmetric(
                              horizontal: 16, vertical: 8),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20),
                          ),
                        ),
                        child: Text(
                          S.current.skip,
                          style: tt.labelLarge?.copyWith(
                            color: ColorRes.anisNavy,
                            fontWeight: FontWeight.w700,
                            fontSize: 13,
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

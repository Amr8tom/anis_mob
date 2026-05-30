import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:flutter/material.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../core/widgets/buttons/d_button.dart';
import '../../../../generated/l10n.dart';
import '../models/onboarding_model.dart';
import '../widgets/onboarding_page_widget.dart';
import '../widgets/page_indicator.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  void _onPageChanged(int page) {
    setState(() {
      _currentPage = page;
    });
  }

  void _goToNextPage() {
    if (_currentPage < OnboardingData.getPages(context).length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    }else{
      context.pushNamed(DRoutesName.loginRoute);
    }
  }

  void _goToPreviousPage() {
    if (_currentPage > 0) {
      _pageController.previousPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorRes.white,
      body: Stack(
        children: [
          Column(
            children: [
              /// PageView
              Expanded(
                child: PageView.builder(
                  controller: _pageController,
                  onPageChanged: _onPageChanged,
                  itemCount: OnboardingData.getPages(context).length,
                  itemBuilder: (context, index) {
                    return OnboardingPageWidget(
                      page: OnboardingData.getPages(context)[index],
                    );
                  },
                ),
              ),

              /// Bottom section with indicator and buttons
              Padding(
                padding: EdgeInsets.only(left:AppSizes.xl,right:AppSizes.xl,bottom: AppSizes.xl),
                child: Column(
                  children: [
                    PageIndicator(
                      currentPage: _currentPage,
                      totalPages: OnboardingData.getPages(context).length,
                    ),
                    Row(
                      children: [
                        Expanded(
                          child: DButton(
                            borderRadius: AppSizes.borderRadiusXXLg,
                            height: AppSizes.heightcontainer,
                            text: S.current.previous,
                            onPressed: _goToPreviousPage,
                            variant: DButtonVariant.secondary,
                            size: DButtonSize.medium,
                            useShadow: true,
                          ),
                        ),
                        const Sizer(width: 8),
                        Expanded(
                          child: DButton(
                            borderRadius: AppSizes.borderRadiusXXLg,
                            height: AppSizes.heightcontainer,
                            text: S.current.continuee,
                            onPressed: _goToNextPage,
                            variant: DButtonVariant.primary,
                            size: DButtonSize.medium,
                          ),
                        ),
                      ],
                    ),
                  ],

                ),
              ),

              // /// Buttons
              // Padding(
              //   padding: EdgeInsets.symmetric(horizontal: AppSizes.padding*3),
              //   child:
              // ),


            ],
          ),
        ],
      ),
    );
  }
}

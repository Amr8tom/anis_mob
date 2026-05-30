import 'package:flutter/material.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/asset_resoures.dart';
import '../../../../core/constants/colors.dart';
import '../models/onboarding_model.dart';

class OnboardingPageWidget extends StatelessWidget {
  final OnboardingModel page;

  const OnboardingPageWidget({super.key, required this.page});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      height: double.infinity,
      color: ColorRes.yellow,
      child: Stack(
        children: [
          ///  Background Image
          Positioned(
            top: 0,
            left: 0,
            right: 0,
            child: Image.asset(
              AssetRes.backGroundImage,
              width: AppSizes.fullWidth,
              fit: BoxFit.fitWidth,
            ),
          ),

          Column(
            children: [
              /// Top section with illustration
              Expanded(
                flex: 10,
                child: Padding(
                  padding: EdgeInsets.symmetric(
                    horizontal: AppSizes.xl,
                    vertical: AppSizes.xl,
                  ),
                  child: Center(
                    child: Image.asset(page.imagePath, fit: BoxFit.contain),
                  ),
                ),
              ),

              /// Bottom section with text content
              Expanded(
                flex: 5,
                child: Container(
                  decoration: BoxDecoration(
                    color: ColorRes.white,
                    borderRadius: BorderRadius.only(
                      topLeft: Radius.circular(40),
                      topRight: Radius.circular(40),
                    ),
                  ),
                  width: double.infinity,
                  padding: EdgeInsets.only(
                    left: AppSizes.xl,
                    right: AppSizes.xl,
                    top: AppSizes.xl,
                  ),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Sizer(height: 10,),
                      /// Title
                      Text(
                        page.title,
                        style: Theme.of(
                          context,
                        ).textTheme.headlineLarge!.copyWith(letterSpacing: 1.2),
                        textAlign: TextAlign.center,
                        maxLines: 5,
                      ),

                      const Sizer(height: 40),

                      /// Description
                      Flexible(
                        fit: FlexFit.tight,
                        child: Text(
                          page.description,
                          style: Theme.of(context).textTheme.bodyLarge!
                              .copyWith(color: ColorRes.darkGrey, height: 1.5),
                          maxLines: 7,
                          textAlign: TextAlign.center,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

import 'package:anis/core/constants/asset_resoures.dart';
import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';



/// Pure-Flutter illustrated panels for each onboarding page.
class OnboardingIllustration extends StatelessWidget {
  final int pageIndex;
  const OnboardingIllustration({super.key, required this.pageIndex});

  @override
  Widget build(BuildContext context) {
    switch (pageIndex) {
      case 0:  return Lottie.asset(AssetRes.aniLottie);
      case 1:  return Lottie.asset(AssetRes.teamWorkLottie);
      default: return Lottie.asset(AssetRes.girlChatLottie);
    }
  }
}

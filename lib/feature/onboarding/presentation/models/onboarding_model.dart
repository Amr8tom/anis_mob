import 'package:flutter/material.dart';
import '../../../../generated/l10n.dart';
import '../../../../core/constants/asset_resoures.dart';

class OnboardingModel {
  final String title;
  final String description;
  final String imagePath;

  const OnboardingModel({
    required this.title,
    required this.description,
    required this.imagePath,
  });
}

// Onboarding data
class OnboardingData {
  static List<OnboardingModel> getPages(BuildContext context) {
    
    return [
      OnboardingModel(
        title: S.current.onboarding1Title,
        description: S.current.onboarding1Desc,
        imagePath: AssetRes.onboarding1,
      ),
      OnboardingModel(
        title: S.current.onboarding2Title,
        description: S.current.onboarding2Desc,
        imagePath: AssetRes.onboarding2,
      ),
      OnboardingModel(
        title: S.current.onboarding3Title,
        description: S.current.onboarding3Desc,
        imagePath: AssetRes.onboarding3,
      ),
    ];
  }
}

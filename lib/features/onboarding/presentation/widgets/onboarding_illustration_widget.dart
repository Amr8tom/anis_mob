import 'package:flutter/material.dart';

import 'find_buddy_illustration.dart';
import 'study_sessions_illustration.dart';
import 'workspace_pass_illustration.dart';

/// Pure-Flutter illustrated panels for each onboarding page.
class OnboardingIllustration extends StatelessWidget {
  final int pageIndex;
  const OnboardingIllustration({super.key, required this.pageIndex});

  @override
  Widget build(BuildContext context) {
    switch (pageIndex) {
      case 0:  return const WorkspacePassIllustration();
      case 1:  return const StudySessionsIllustration();
      default: return const FindBuddyIllustration();
    }
  }
}

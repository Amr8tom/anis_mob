import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';

class BuddyPageTitle extends StatelessWidget {
  const BuddyPageTitle({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          S.current.findBuddy,
          textAlign: TextAlign.start,
          style: tt.headlineMedium?.copyWith(
            fontWeight: FontWeight.w800,
            color: ColorRes.anisNavy,
          ),
        ),
        const Sizer(height: 4),
        Text(
          S.current.buddyScreenSubtitle,
          textAlign: TextAlign.start,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
        ),
      ],
    );
  }
}

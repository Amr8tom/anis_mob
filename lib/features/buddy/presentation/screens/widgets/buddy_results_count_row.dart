import 'package:flutter/material.dart';

import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../generated/l10n.dart';

class BuddyResultsCountRow extends StatelessWidget {
  final int count;

  const BuddyResultsCountRow({super.key, required this.count});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.md,
        AppSizes.padding,
        AppSizes.xs,
      ),
      child: Text(
        S.current.resultsCount(count),
        style: Theme.of(context).textTheme.bodySmall?.copyWith(
              fontWeight: FontWeight.w600,
              color: ColorRes.anisTextMuted,
            ),
      ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';

/// Bold section heading above each form card.
class SessionSectionLabel extends StatelessWidget {
  final String label;
  const SessionSectionLabel(this.label, {super.key});

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
            fontWeight: FontWeight.w800,
            color: ColorRes.anisNavy,
          ),
    );
  }
}

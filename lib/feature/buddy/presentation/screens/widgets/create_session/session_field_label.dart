import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';

/// Small muted label rendered above each input field.
class SessionFieldLabel extends StatelessWidget {
  final String label;
  const SessionFieldLabel(this.label, {super.key});

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodySmall?.copyWith(
            fontWeight: FontWeight.w700,
            color: ColorRes.anisTextMuted,
          ),
    );
  }
}

import 'package:flutter/material.dart';

import '../../../../../../common/custom_ui.dart';

class WorkspaceErrorSection extends StatelessWidget {
  final String message;
  final VoidCallback onRetry;

  const WorkspaceErrorSection({
    super.key,
    required this.message,
    required this.onRetry,
  });

  @override
  Widget build(BuildContext context) {
    return SliverFillRemaining(
      child: CustomUI.appErrorState(
        context: context,
        message: message,
        onRetry: onRetry,
      ),
    );
  }
}


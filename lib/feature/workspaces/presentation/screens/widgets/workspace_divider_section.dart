import 'package:flutter/material.dart';

import '../../../../../core/constants/colors.dart';

class WorkspaceDividerSection extends StatelessWidget {
  const WorkspaceDividerSection({super.key});

  @override
  Widget build(BuildContext context) {
    return SliverToBoxAdapter(
      child: Container(
        height: 1,
        color: ColorRes.accent.withValues(alpha: 0.5),
      ),
    );
  }
}



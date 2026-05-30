import 'package:flutter/material.dart';

import '../../../../../common/widgets/sizeboxs/Sizer.dart';

class WorkspaceFooterSpacingSection extends StatelessWidget {
  const WorkspaceFooterSpacingSection({super.key});

  @override
  Widget build(BuildContext context) {
    return const SliverToBoxAdapter(child: Sizer(height: 24));
  }
}


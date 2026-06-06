import 'package:flutter/material.dart';
import 'package:skeletonizer/skeletonizer.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_card.dart';

class WorkspaceLoadingSection extends StatelessWidget {
  const WorkspaceLoadingSection({super.key});

  @override
  Widget build(BuildContext context) {
    return SliverPadding(
      padding: EdgeInsets.only(top: AppSizes.md),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (_, i) => Skeletonizer(
            enabled: true,
            child: WorkspaceCard(
              workspace: _skeletonWorkspace(i),
            ),
          ),
          childCount: 4,
        ),
      ),
    );
  }
}

WorkspaceEntity _skeletonWorkspace(int i) {
  return WorkspaceEntity(
    id: 'sk_$i',
    name: '████████████████',
    address: '████████████',
    currentOccupancy: 10,
    capacity: 30,
    status: WorkspaceStatus.open,
    distanceKm: 1.0,
    openTime: '8:00 ص',
    closeTime: '10:00 م',
    amenities: const [],
  );
}


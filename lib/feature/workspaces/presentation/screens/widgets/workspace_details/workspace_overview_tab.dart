import 'package:anis/feature/workspaces/presentation/screens/widgets/workspace_details/workspace_gallery_section.dart';
import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_description_section.dart';
import 'workspace_location_button.dart';

class WorkspaceOverviewTab extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceOverviewTab({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          WorkspaceGallerySection(images: workspace.galleryImages),
          const Sizer(height: 20),
          WorkspaceDescriptionSection(description: workspace.description),
          const Sizer(height: 24),
          WorkspaceLocationButton(
            latitude: workspace.latitude,
            longitude: workspace.longitude,
            label: 'Google Maps',
          ),
          const Sizer(height: 24),
        ],
      ),
    );
  }
}


import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../domain/entity/workspace_entity.dart';
import 'workspace_gallery.dart';
import 'workspace_description_section.dart';
import 'workspace_billing_section.dart';
import 'workspace_amenities_section.dart';
import 'workspace_drinks_section.dart';
import 'workspace_location_card.dart';

class WorkspaceInfoTab extends StatelessWidget {
  final WorkspaceEntity workspace;

  const WorkspaceInfoTab({super.key, required this.workspace});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Gallery
          if (workspace.galleryImages.isNotEmpty) ...[
            WorkspaceGallery(images: workspace.galleryImages),
            Sizer(height: AppSizes.md),
          ],

          // Description
          if (workspace.description.isNotEmpty) ...[
            WorkspaceDescriptionSection(description: workspace.description),
            Sizer(height: AppSizes.md),
          ],

          WorkspaceBillingSection(
            workspace: workspace,
          ),
          Sizer(height: AppSizes.md),

          // Amenities
          if (workspace.amenities.isNotEmpty) ...[
            WorkspaceAmenitiesSection(amenities: workspace.amenities),
            Sizer(height: AppSizes.md),
          ],

          // Drinks
          WorkspaceDrinksSection(drinks: workspace.drinks),
          Sizer(height: AppSizes.md),

          // Location
          WorkspaceLocationCard(
            latitude: workspace.latitude,
            longitude: workspace.longitude,
            address: workspace.address,
          ),
          Sizer(height: AppSizes.xl),
        ],
      ),
    );
  }
}

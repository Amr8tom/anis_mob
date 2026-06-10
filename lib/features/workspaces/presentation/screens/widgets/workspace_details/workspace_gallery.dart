import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import 'workspace_gallery_image.dart';

class WorkspaceGallery extends StatelessWidget {
  final List<String> images;
  const WorkspaceGallery({super.key, required this.images});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 200.0,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
        itemCount: images.length,
        separatorBuilder: (_, __) => Sizer(width: AppSizes.sm),
        itemBuilder: (_, index) {
          return ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            child: Container(
              width: 280.0,
              color: ColorRes.anisChipBg,
              child: WorkspaceGalleryImage(source: images[index]),
            ),
          );
        },
      ),
    );
  }
}

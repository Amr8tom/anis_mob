import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../../../../common/custom_ui.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import 'workspace_gallery_image.dart';

class WorkspaceGallerySection extends StatelessWidget {
  final List<String> images;

  const WorkspaceGallerySection({super.key, required this.images});

  @override
  Widget build(BuildContext context) {
    if (images.isEmpty) {
      return CustomUI.anisEmptyState(
        context: context,
        icon: Icons.photo_outlined,
        title: S.current.gallery,
        subtitle: S.current.noData,
      );
    }

    return SizedBox(
      height: 170.h,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
        itemBuilder: (context, index) {
          final image = images[index];
          return ClipRRect(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            child: Container(
              width: 260.w,
              color: ColorRes.accent,
              child: WorkspaceGalleryImage(source: image),
            ),
          );
        },
        separatorBuilder: (_, __) => const Sizer(width: 12),
        itemCount: images.length,
      ),
    );
  }
}

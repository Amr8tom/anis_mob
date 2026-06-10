import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';

import 'gallery_image_placeholder.dart';

class WorkspaceGalleryImage extends StatelessWidget {
  final String source;
  final BoxFit fit;

  const WorkspaceGalleryImage({
    super.key,
    required this.source,
    this.fit = BoxFit.cover,
  });

  @override
  Widget build(BuildContext context) {
    final uri = Uri.tryParse(source);
    final isRemote = uri != null &&
        (uri.scheme.toLowerCase() == 'http' ||
            uri.scheme.toLowerCase() == 'https');

    if (isRemote) {
      return Image.network(
        source,
        fit: fit,
        loadingBuilder: (context, child, progress) {
          if (progress == null) return child;
          return const Center(
            child: CircularProgressIndicator(
              color: ColorRes.anisGreen,
              strokeWidth: 2,
            ),
          );
        },
        errorBuilder: (_, __, ___) => const GalleryImagePlaceholder(),
      );
    }

    return Image.asset(
      source,
      fit: fit,
      errorBuilder: (_, __, ___) => const GalleryImagePlaceholder(),
    );
  }
}

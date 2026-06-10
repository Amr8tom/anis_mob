import 'package:flutter/material.dart';
import '../../../../../../core/constants/colors.dart';

class GalleryImagePlaceholder extends StatelessWidget {
  const GalleryImagePlaceholder({super.key});

  @override
  Widget build(BuildContext context) {
    return const ColoredBox(
      color: ColorRes.accent,
      child: Center(
        child: Icon(
          Icons.image_not_supported_outlined,
          color: ColorRes.anisHintText,
        ),
      ),
    );
  }
}

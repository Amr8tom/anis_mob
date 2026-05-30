import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:cached_network_image/cached_network_image.dart';

class BackgroundImage extends StatelessWidget {
  final String path;
  final BoxFit fit;
  final bool isNetworkImage;
  final bool isSvgImage;
  final bool isPositioned;

  const BackgroundImage({
    super.key,
    required this.path,
    this.isNetworkImage = false,
    this.isSvgImage = false,
    required this.fit,
    this.isPositioned = true,
  });

  @override
  Widget build(BuildContext context) {
    return isPositioned? Positioned.fill(
      child:
          isNetworkImage
              ? CachedNetworkImage(imageUrl: path, fit: fit)
              : isSvgImage
              ? SvgPicture.asset(path, fit: fit)
              : Image.asset(path, fit: fit),
    ) :  isNetworkImage
        ? CachedNetworkImage(imageUrl: path, fit: fit)
        : isSvgImage
        ? SvgPicture.asset(path, fit: fit)
        : Image.asset(path, fit: fit);
  }
}

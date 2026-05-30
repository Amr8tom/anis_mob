import 'package:flutter/material.dart';
import 'package:skeletonizer/skeletonizer.dart';

class ShimmerLoadingWidget extends StatelessWidget {
  final double width;
  final double height;
  final double borderRadius;
  const ShimmerLoadingWidget({
    super.key,
    required this.width,
    required this.height,
    required this.borderRadius,
  });
  @override
  Widget build(BuildContext context) {
    return Skeletonizer(
      enabled: true, /// Set this to true to enable the shimmer effect
      child: Container(
        width: width,
        height: height,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(borderRadius),
        ),
      ),
    );
  }
}

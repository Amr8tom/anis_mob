import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../../core/constants/colors.dart';

class RatingStars extends StatelessWidget {
  final num rating;
  final int maxRating;
  final double iconSize;
  final Color? color;

  const RatingStars(
      {super.key,
        required this.rating,
        this.maxRating = 5,
        this.color,
        this.iconSize = 14});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: kToolbarHeight * .4.h,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        shrinkWrap: true,
        itemBuilder: (context, index) => Icon(
          Icons.star,
          size: iconSize,
          color: rating > (index)
              ? color ?? ColorRes.primary
              : ColorRes.grey,
        ),
        itemCount: maxRating,
      ),
    );
  }
}

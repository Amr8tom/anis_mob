import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class Sizer extends StatelessWidget {
  const Sizer({super.key, this.width = 0, this.height = 0});

  final double width;
  final double height;

  @override
  Widget build(BuildContext context) {
    return SizedBox(width: width.w, height: height.h);
  }
}

import 'package:flutter/material.dart';
import '../../../../core/constants/app_sizes.dart';

class PageIndicator extends StatelessWidget {
  final int currentPage;
  final int totalPages;

  const PageIndicator({
    super.key,
    required this.currentPage,
    required this.totalPages,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List.generate(
        totalPages,
        (index) => Container(
          margin: EdgeInsets.symmetric(horizontal: AppSizes.xs),
          width: currentPage == index ? AppSizes.xl * 1.5 : AppSizes.xs,
          height: AppSizes.xs,
          decoration: BoxDecoration(
            color: currentPage == index
                ? const Color(0xFF2C2C2C)
                : const Color(0xFFE0E0E0),
            borderRadius: BorderRadius.circular(AppSizes.xs / 2),
          ),
        ),
      ),
    );
  }
}

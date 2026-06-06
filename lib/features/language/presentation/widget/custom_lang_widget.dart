import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/constants/app_sizes.dart';

class CustomLangWidget extends StatelessWidget {
  final String langName;
  final String imagPath;
  final VoidCallback onTab;
  final double? hight;
  final double? width;
  final bool isSvg;
  
  const CustomLangWidget({
    super.key,
    required this.imagPath,
    required this.langName,
    required this.onTab,
    this.hight,
    this.width,
    this.isSvg = false,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTab,
      child: Padding(
        padding: EdgeInsets.all(AppSizes.ld),
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
            gradient: LinearGradient(
              colors: ColorRes.langGrad, 
              begin: Alignment.topLeft,
            ),
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Spacer(),
              isSvg
                  ? SvgPicture.asset(
                      imagPath,
                      height: hight,
                    )
                  : Image.asset(
                      imagPath,
                      height: hight,
                      fit: BoxFit.fill,
                    ),
              const Spacer(),
              Text(
                langName,
                style: Theme.of(context).textTheme.headlineSmall!.copyWith(
                  color: Colors.white,
                ),
              ),
              const Spacer(),
            ],
          ),
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:lottie/lottie.dart';
import '../../core/constants/asset_resoures.dart';
import '../../core/device/device_utility.dart';
import '../../core/constants/app_sizes.dart';
import '../../core/constants/colors.dart';
import '../../feature/navigation/presentation/controllers/navigation_cubit.dart';

class CustomLottieFloatingAvtionBottom extends StatelessWidget {
  const CustomLottieFloatingAvtionBottom({super.key});
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.read<NavigationCubit>().changeIndex(4);
      },
      child: RepaintBoundary(
        child: CircleAvatar(
          backgroundColor: ColorRes.yellow,
          radius: DDeviceUtils.getScreenWidth(context)/10,
          child: SizedBox(
        
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                SizedBox(
                  width: AppSizes.imageSize*1.2,
                  child: Transform.scale(
                    scale: 1.3,
                    child: ColorFiltered(
                      colorFilter: ColorFilter.mode(
                        ColorRes.primary, // Your desired color
                        BlendMode.srcIn,
                      ),
                      child: Lottie.asset(
                        AssetRes.homeLottieIcon,
                        fit: BoxFit.fill,
                        repeat: true,
                        height: AppSizes.imageSize * 1.2,
                        width: AppSizes.imageSize * 1.2,
                      ),
                    ),
                  ),
                ),
                // SvgPicture.asset(
                //   AssetRes.kaaba,
                //   width: AppSizes.iconLg,
                //   height: AppSizes.iconLg,
                //
                // ),
                // Sizer(height: 3,),
                // Text(S.current.home,style:Theme.of(context).textTheme.headlineSmall!.copyWith(color:ColorRes.primary),)
              ],
            ),
          ),
        ),
      ),
    );
  }
}

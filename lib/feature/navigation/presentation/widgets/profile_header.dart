import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/core/constants/app_sizes.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/asset_resoures.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controllers/navigation_cubit.dart';

class ProfileHeader extends StatelessWidget {
  final String? userName;
  final String? userImage;

  const ProfileHeader({
    super.key,
    this.userName,
    this.userImage,
  });

  @override
  Widget build(BuildContext context) {
    final controller = context.read<NavigationCubit>();

    return GestureDetector(
      onTap: () {
        controller.changeIndex(3);
      },
      child: SizedBox(
        width: 250,
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            /// Profile avatar with enhanced design
            Container(
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: Colors.white,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.2),
                    blurRadius: 10,
                    offset: const Offset(0, 3),
                  ),
                  BoxShadow(
                    color: Colors.white.withValues(alpha: 0.5),
                    blurRadius: 8,
                    offset: const Offset(0, -2),
                  ),
                ],
              ),
              padding: EdgeInsets.all(3.w), // This creates the white border
              child: Container(
                // width: 100,
                // height: 90,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: ColorRes.primary.withValues(alpha: 0.1),
                ),
                child: ClipOval(
                    child: userImage != null
                        ? Image.network(
                            userImage!,
                            fit: BoxFit.cover,

                          )
                        : Sizer()),
              ),
            ),

            Sizer(width: 8),

            /// User info column
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    S.current.welcome,
                    style: TextStyle(
                      color: Colors.white.withValues(alpha: 0.9),
                      fontSize: AppSizes.fontSizeMd,
                      fontWeight: FontWeight.w800,
                      height: 1.3,
                      letterSpacing: 0.2,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const Sizer(height: 2),
                  Text(
                    "amr alaa ali",
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: AppSizes.fontSizeSm,
                      fontWeight: FontWeight.w600,
                      height: 1.2,
                      letterSpacing: 0.3,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  // Text(
                  //   S.current.welcome,
                  //   style: TextStyle(
                  //     color: Colors.white.withValues(alpha: 0.9),
                  //     fontSize: 10.sp,
                  //     fontWeight: FontWeight.w500,
                  //     height: 1.3,
                  //     letterSpacing: 0.2,
                  //   ),
                  //   maxLines: 1,
                  //   overflow: TextOverflow.ellipsis,
                  // ),
                  // const Sizer(height: 2),
                  // Text(
                  //   controller.state.user?.fullName ?? "مصطفى ذكريا محمد",
                  //   style: TextStyle(
                  //     color: Colors.white,
                  //     fontSize: 11.sp,
                  //     fontWeight: FontWeight.w700,
                  //     height: 1.2,
                  //     letterSpacing: 0.3,
                  //   ),
                  //   maxLines: 1,
                  //   overflow: TextOverflow.ellipsis,
                  // ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

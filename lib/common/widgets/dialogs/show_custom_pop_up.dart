import 'package:flutter/material.dart';
import '../../../core/constants/app_sizes.dart';
import '../../../core/constants/colors.dart';
import '../../../generated/l10n.dart';

void showOTPPopUp({required BuildContext context, required String email}) {
  showDialog(
    context: context,
    barrierDismissible: false,
    builder: (BuildContext context) {
      return Dialog(
        child: Directionality(
          textDirection: TextDirection.ltr,
          child: Container(
            padding: EdgeInsets.symmetric(horizontal: AppSizes.padding / 3),
            decoration: BoxDecoration(
              color: ColorRes.white,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLarge),
            ),
            height: AppSizes.containerLarge * 1.5,
            child: Stack(
              children: [
                Padding(
                  padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      /// Title
                      Padding(
                        padding: EdgeInsets.symmetric(
                          vertical: AppSizes.padding,
                        ),
                        child: Text(
                          S.current.resetPassword,
                          style: Theme.of(context).textTheme.headlineMedium!,
                          textAlign: TextAlign.center,
                          maxLines: 5,
                        ),
                      ),

                      /// make size

                      /// Description
                      Flexible(
                        child: Text(
                          S.current.enterEmailToResetPassword,
                          style: Theme.of(context).textTheme.bodyLarge!
                              .copyWith(color: ColorRes.darkGrey, height: 1.5),
                          maxLines: 7,
                          textAlign: TextAlign.center,
                        ),
                      ),

                      /// email
                      // Directionality(
                      //   textDirection: TextDirection.rtl,
                      //   child: AuthTextField(
                      //     hint: S.current.email,
                      //     controller: TextEditingController(),
                      //     prefixIcon: Icon(Icons.email, color: ColorRes.grey),
                      //   ),
                      // ),
                      // const Sizer(height: 16),
                      // AuthButton(
                      //   text: S.current.send,
                      //   onPressed: () {},
                      //   width: double.infinity,
                      //   height: AppSizes.buttonHeight,
                      //   textColor: ColorRes.white,
                      //   backgroundColor: ColorRes.primary,
                      // ),
                    ],
                  ),
                ),
                Positioned(
                  top: 0,
                  left: 0,
                  child: IconButton(
                    onPressed: () {
                      Navigator.of(context).pop();
                    },
                    icon: Icon(Icons.close, color: ColorRes.grey),
                  ),
                ),
              ],
            ),
          ),
        ),
      );
    },
  );
}

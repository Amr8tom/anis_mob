import 'package:flutter/material.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import 'package:anis/core/widgets/buttons/d_button.dart';
import '../../../core/constants/app_sizes.dart';

import '../../../core/constants/colors.dart';
import '../../../core/device/device_utility.dart';
import '../../../core/utils/helpers/background_image.dart';
import '../../../generated/l10n.dart';
import '../sizeboxs/sizer.dart';

void customDialogImgTitleDes({
  required BuildContext context,
  required String title,
  required String des,
  String? orderNumber,
  required String imgPath,
  String? button1,
  button2,
  VoidCallback? onTab1,
  VoidCallback? onTab2,
  bool isSvg = false,
  double? width,
  height,
}) {
  showDialog(
    context: context,
    barrierDismissible: false,
    builder: (BuildContext context) {
      return Dialog(
        child: Container(
          padding: EdgeInsets.all(AppSizes.padding),
          decoration: BoxDecoration(
            color: ColorRes.white,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLarge),
          ),
          height: DDeviceUtils.getScreenHeight(context) / 3,
          width: DDeviceUtils.getScreenWidth(context) / 1.1,
          child: Stack(
            children: [
              Positioned(
                top: 0,
                right: 0,
                child: IconButton(
                  icon: Icon(
                    Icons.close,
                    color: ColorRes.grey2,
                    size: AppSizes.iconMd,
                  ),
                  onPressed: () {
                    context.pop();
                  },
                ),
              ),
              Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  SizedBox(
                    width: width ?? AppSizes.containerSmall * 1.5,
                    height: height ?? AppSizes.containerSmall * 1.5,
                    child: BackgroundImage(
                      isSvgImage: isSvg,
                      isPositioned: false,
                      path: imgPath,
                      fit: BoxFit.fill,
                    ),
                  ),
                  const Sizer(height: 16, width: double.infinity),
                  Text(
                    title,
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: ColorRes.black, fontWeight: FontWeight.bold),
                  ),
                  const Sizer(height: 8),
                  Text(
                    orderNumber == null
                        ? des
                        : "${S.current.orderNumber} $orderNumber",
                    style: Theme.of(
                      context,
                    ).textTheme.bodyMedium?.copyWith(color: ColorRes.grey2),
                    maxLines: 5,
                  ),
                  const Sizer(height: 20),
                  if (button1 != null && button2 != null) ...[
                    SizedBox(
                      width: AppSizes.fullWidth,
                      child: Row(
                        children: [
                          Expanded(
                            child: DButton(
                              text: button1,
                              height: AppSizes.heightcontainer,
                              borderRadius: AppSizes.borderRadiusXXLg,
                              size: DButtonSize.medium,
                              variant: DButtonVariant.primary,
                              onPressed: onTab1,
                            ),
                          ),
                          Sizer(width: 8),
                          Expanded(
                            child: DButton(
                              text: button2,
                              height: AppSizes.heightcontainer,
                              borderRadius: AppSizes.borderRadiusXXLg,
                              size: DButtonSize.medium,
                              variant: DButtonVariant.secondary,
                              onPressed: onTab2,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ],
              ),
            ],
          ),
        ),
      );
    },
  );
}

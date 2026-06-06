import 'package:flutter/material.dart';
import 'package:anis/core/extentions/navigation_extension.dart';
import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/app_sizes.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../core/utils/validators.dart';
import '../../../../../generated/l10n.dart';
import '../auth_button.dart';

class OtpForm extends StatefulWidget {
  const OtpForm({super.key});

  @override
  State<OtpForm> createState() => _OtpFormState();
}

class _OtpFormState extends State<OtpForm> {
  final _formKey = GlobalKey<FormState>();
  final String _otpCode = '';
  String? _errorMessage;

  void _handleSubmitOtp(BuildContext context) {
    setState(() {
      _errorMessage = Validators.otp(_otpCode, length: 5);
    });

    if (_errorMessage == null) {
      // OTP is valid, proceed to next screen
      context.pushNamed(DRoutesName.addNewPasswordRoute);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Container(
        decoration: BoxDecoration(
          color: ColorRes.white,
          borderRadius: BorderRadius.only(
            topLeft: Radius.circular(AppSizes.borderRadiusXXLg),
            topRight: Radius.circular(AppSizes.borderRadiusXXLg),
          ),
        ),
        width: double.infinity,
        padding: EdgeInsets.only(left: AppSizes.xl, right: AppSizes.xl),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            /// Title
            Text(
              S.current.otp,
              style: Theme.of(
                context,
              ).textTheme.headlineMedium,
              textAlign: TextAlign.center,
              maxLines: 5,
            ),

            /// make size
            const Sizer(
              height: 12,
            ),

            /// Description
            Flexible(
              child: Text(
                S.current.enterOtp,
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: ColorRes.darkGrey,
                      height: 1.5,
                    ),
                maxLines: 7,
                textAlign: TextAlign.center,
              ),
            ),

            /// make size
            const Sizer(height: 70),

            /// OTP fields
            // OtpTextField(
            //   fieldWidth: AppSizes.xl*1.8,
            //   fieldHeight: AppSizes.xxl*1.5,
            //   numberOfFields: 5,
            //   borderColor: _errorMessage != null ? ColorRes.error : ColorRes.darkerGrey,
            //   showFieldAsBox: true,
            //   onCodeChanged: (String code) {
            //     setState(() {
            //       _otpCode = code;
            //       _errorMessage = null; // Clear error on change
            //     });
            //   },
            //   onSubmit: (String verificationCode) {
            //     setState(() {
            //       _otpCode = verificationCode;
            //     });
            //     _handleSubmitOtp(context);
            //   }, // end onSubmit
            // ),
            const Sizer(
              height: 12,
            ),

            // Error message
            if (_errorMessage != null)
              Padding(
                padding: EdgeInsets.only(top: AppSizes.sm),
                child: Text(
                  _errorMessage!,
                  style: Theme.of(context).textTheme.bodySmall!.copyWith(
                        color: ColorRes.error,
                      ),
                ),
              ),

            // const Sizer(height: 6),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: AppSizes.padding * 2),
              child: AuthButton(
                text: S.current.sendOtp,
                onPressed: () => _handleSubmitOtp(context),
                width: double.infinity,
                height: AppSizes.buttonHeight,
                textColor: ColorRes.white,
                backgroundColor: ColorRes.primary,
              ),
            ),
            const Sizer(
              height: 12,
            ),
          ],
        ),
      ),
    );
  }
}

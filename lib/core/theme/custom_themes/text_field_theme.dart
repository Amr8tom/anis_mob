import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

import '../../constants/colors.dart';

class DTextFormFieldTheme {
  DTextFormFieldTheme._();

  static InputDecorationTheme lightInputDecorationTheme = InputDecorationTheme(
    errorMaxLines: 2,
    prefixIconColor: ColorRes.black,
    suffixIconColor: ColorRes.black,
    labelStyle: const TextStyle().copyWith(
        fontWeight: FontWeight.w400, fontSize: 14.sp, color: Colors.grey),
    hintStyle: const TextStyle().copyWith(
        fontWeight: FontWeight.w400, fontSize: 14.sp, color: Colors.black),
    errorStyle: const TextStyle()
        .copyWith(fontWeight: FontWeight.w400, fontStyle: FontStyle.normal),
    floatingLabelStyle:
        const TextStyle().copyWith(color: ColorRes.black.withOpacity(0.8)),
    border: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: ColorRes.black),
    ),
    enabledBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: ColorRes.black),
    ),
    focusedBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: ColorRes.black),
    ),
    errorBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.red),
    ),
    focusedErrorBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.orange),
    ),
  );

  static InputDecorationTheme darkInputDecorationTheme = InputDecorationTheme(
    errorMaxLines: 2,
    prefixIconColor: Colors.grey,
    suffixIconColor: Colors.grey,
    labelStyle:
        const TextStyle().copyWith(fontSize: 14.sp, color: Colors.white),
    hintStyle: const TextStyle().copyWith(fontSize: 14.sp, color: Colors.white),
    floatingLabelStyle:
        const TextStyle().copyWith(color: Colors.white.withOpacity(0.8)),
    border: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.grey),
    ),
    enabledBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.grey),
    ),
    focusedBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.white),
    ),
    errorBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 1.w, color: Colors.red),
    ),
    focusedErrorBorder: const OutlineInputBorder().copyWith(
      borderRadius: BorderRadius.circular(14.r),
      borderSide: BorderSide(width: 2.w, color: Colors.orange),
    ),
  );
}

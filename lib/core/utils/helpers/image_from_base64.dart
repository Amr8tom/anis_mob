import 'dart:convert';
import 'package:flutter/material.dart';
import '../../constants/app_sizes.dart';

Image ImageFromBase64String({
  required String base64String,
  double? width,
  double? height,
  fit = BoxFit.fill,
}) {
  return Image.memory(
    base64Decode(base64String),
    fit: fit,
    width: width,
    height: height,
    errorBuilder: (context, error, stackTrace) {
      return  Icon(
        Icons.image_not_supported,
        color: Colors.grey,
        size: AppSizes.iconXLarge,
      );
    },
  );
}

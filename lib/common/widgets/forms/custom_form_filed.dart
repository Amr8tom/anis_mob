import 'package:flutter/material.dart';
import '../../../core/constants/colors.dart';
import '../../../generated/l10n.dart';

class CustomTextFromFiled extends StatelessWidget {
  final String label;
  final String validateError;
  final IconData? icon;
  final bool isMail;
  final TextEditingController controller;
  final bool isPhone;
  final int maxLines;
  final double? iconSize ;
  final Color? border;
  final FormFieldValidator<String>?  validator;

  const CustomTextFromFiled({
    super.key,
    required this.label,
    this.isMail = false,
    this.isPhone = false,
    required this.validateError,
     this.icon,
    required this.controller,
    this.maxLines = 1, this.iconSize, this.border,  this.validator,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(

      maxLines: maxLines,
      controller: controller,
      keyboardType: isPhone ? TextInputType.phone : TextInputType.text,
      decoration: InputDecoration(
        alignLabelWithHint: true,
        labelText: label,
        border: const OutlineInputBorder(),
        suffixIcon: Icon(icon,size: iconSize, color: ColorRes.grey,),
        enabledBorder: OutlineInputBorder(
          borderSide: BorderSide(color:border?? ColorRes.grey),
        ),
        focusedBorder: OutlineInputBorder(
          borderSide: BorderSide(color: border ??ColorRes.grey, width: 2.0),
        ),
        errorBorder: OutlineInputBorder(
          borderSide: BorderSide(color: border ??ColorRes.grey),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderSide: BorderSide(color: border ??ColorRes.grey, width: 2.0),
        ),
      ),

      validator:validator?? (value) {
        if (isMail) {
          if (value == null || value.isEmpty) {
            return validateError;
          } else if (!RegExp(
            r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$',
          ).hasMatch(value)) {
            return S.current.error;
          }
          return null;
        } else {
          if (value == null || value.isEmpty) {
            return validateError;
          }
          return null;
        }
      },

    );
  }
}

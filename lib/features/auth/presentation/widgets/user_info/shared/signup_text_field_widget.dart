import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Light-mode labeled text field for the sign-up wizard.
/// Matches the same visual language as [LoginDarkField].
class SignupTextField extends StatelessWidget {
  final String label;
  final String hint;
  final IconData icon;
  final ValueChanged<String>? onChanged;
  final TextInputType keyboardType;
  final int maxLines;
  final TextDirection? textDirection;
  final List<TextInputFormatter>? inputFormatters;

  const SignupTextField({
    super.key,
    required this.label,
    required this.hint,
    required this.icon,
    this.onChanged,
    this.keyboardType = TextInputType.text,
    this.maxLines = 1,
    this.textDirection,
    this.inputFormatters,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: tt.labelLarge?.copyWith(
            fontWeight: FontWeight.w600,
            color: ColorRes.anisTextDark,
            fontSize: 13,
          ),
        ),
        const Sizer(height: 7),
        TextField(
          onChanged: onChanged,
          keyboardType: keyboardType,
          maxLines: maxLines,
          textDirection: textDirection,
          inputFormatters: inputFormatters,
          cursorColor: ColorRes.anisGreen,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextDark,
            fontWeight: FontWeight.w500,
          ),
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: tt.bodyMedium?.copyWith(
              color: ColorRes.anisHintText,
              fontWeight: FontWeight.w400,
            ),
            prefixIcon: Icon(icon, color: ColorRes.anisTextSecondary, size: 20),
            filled: true,
            fillColor: ColorRes.anisInputBg,
            contentPadding: EdgeInsets.symmetric(
              horizontal: AppSizes.padding,
              vertical: AppSizes.md,
            ),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide: const BorderSide(
                  color: ColorRes.anisInputBorder, width: 1.5),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              borderSide:
                  const BorderSide(color: ColorRes.anisGreen, width: 2),
            ),
          ),
        ),
      ],
    );
  }
}

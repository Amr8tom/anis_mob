import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';

class ProfileTextField extends StatelessWidget {
  final TextEditingController controller;
  final String label;
  final String hint;
  final IconData icon;
  final TextInputType? keyboardType;
  final TextInputAction? textInputAction;
  final String? Function(String?)? validator;
  final ValueChanged<String>? onSubmitted;

  const ProfileTextField({
    super.key,
    required this.controller,
    required this.label,
    required this.hint,
    required this.icon,
    this.keyboardType,
    this.textInputAction,
    this.validator,
    this.onSubmitted,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: (tt.bodySmall ?? const TextStyle()).copyWith(
            fontSize: 15,
            height: 1.3,
            color: ColorRes.anisTextDark,
            fontWeight: FontWeight.w700,
            overflow: TextOverflow.visible,
          ),
        ),
        const Sizer(height: 7),
        TextFormField(
          controller: controller,
          keyboardType: keyboardType,
          textInputAction: textInputAction,
          validator: validator,
          onFieldSubmitted: onSubmitted,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextDark,
            overflow: TextOverflow.visible,
          ),
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: tt.bodyMedium?.copyWith(
              color: ColorRes.anisTextMuted,
              overflow: TextOverflow.visible,
            ),
            errorStyle: tt.bodySmall?.copyWith(
              color: ColorRes.anisErrorRed,
              overflow: TextOverflow.visible,
            ),
            prefixIcon: Icon(icon, color: ColorRes.anisGreen),
            filled: true,
            fillColor: ColorRes.anisInputBg,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisInputBorder),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              borderSide: const BorderSide(color: ColorRes.anisGreen, width: 2),
            ),
          ),
        ),
      ],
    );
  }
}

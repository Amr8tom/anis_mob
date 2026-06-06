import 'package:flutter/material.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';

/// Light-mode password field with built-in visibility toggle.
/// Password visibility is pure UI state — [StatefulWidget] is appropriate.
class SignupPasswordField extends StatefulWidget {
  final String label;
  final TextEditingController controller;

  const SignupPasswordField({
    super.key,
    required this.label,
    required this.controller,
  });

  @override
  State<SignupPasswordField> createState() => _SignupPasswordFieldState();
}

class _SignupPasswordFieldState extends State<SignupPasswordField> {
  bool _hidden = true;

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          widget.label,
          style: tt.labelLarge?.copyWith(
            fontWeight: FontWeight.w600,
            color: ColorRes.anisTextDark,
            fontSize: 13,
          ),
        ),
        const Sizer(height: 7),
        TextField(
          controller: widget.controller,
          obscureText: _hidden,
          cursorColor: ColorRes.anisGreen,
          style: tt.bodyMedium?.copyWith(
            color: ColorRes.anisTextDark,
            fontWeight: FontWeight.w500,
            letterSpacing: _hidden ? 3 : 0,
          ),
          decoration: InputDecoration(
            hintText: '••••••••',
            hintStyle: tt.bodyMedium?.copyWith(
              color: ColorRes.anisHintText,
              letterSpacing: 0,
            ),
            prefixIcon: const Icon(
              Icons.lock_outline_rounded,
              color: ColorRes.anisTextSecondary,
              size: 20,
            ),
            suffixIcon: GestureDetector(
              onTap: () => setState(() => _hidden = !_hidden),
              child: Icon(
                _hidden
                    ? Icons.visibility_off_outlined
                    : Icons.visibility_outlined,
                color: ColorRes.anisTextSecondary,
                size: 20,
              ),
            ),
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

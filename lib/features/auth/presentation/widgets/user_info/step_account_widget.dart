import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/signup_password_field_widget.dart';
import 'shared/signup_text_field_widget.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

/// Step 0: Account credentials, contact numbers, and password.
class StepAccountWidget extends StatelessWidget {
  final TextEditingController passwordController;
  final TextEditingController confirmPasswordController;

  const StepAccountWidget({
    super.key,
    required this.passwordController,
    required this.confirmPasswordController,
  });

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<UserInfoCubit>();
    final tt = Theme.of(context).textTheme;

    return SingleChildScrollView(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.ld,
        AppSizes.padding,
        AppSizes.xl,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // ── Section heading ────────────────────────────────
          Text(
            S.current.createYourAccount,
            style: tt.titleLarge?.copyWith(
              color: ColorRes.anisNavy,
              fontWeight: FontWeight.w800,
              fontSize: 20,
            ),
          ),
          const Sizer(height: 4),
          Text(
            S.current.nameWillAppearOnProfile,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.anisTextMuted,
              fontSize: 13,
            ),
          ),

          const Sizer(height: 24),

          // ── Full name ──────────────────────────────────────
          SignupTextField(
            label: S.current.full,
            hint: S.current.fullNameHint,
            icon: Icons.person_outline_rounded,
            onChanged: cubit.setName,
          ),
          const Sizer(height: 16),

          SignupTextField(
            label: S.current.phoneNumber,
            hint: S.current.phoneHint,
            icon: Icons.phone_outlined,
            keyboardType: TextInputType.text,
            textDirection: TextDirection.ltr,
            inputFormatters: [
              FilteringTextInputFormatter.allow(RegExp(r'[0-9+]')),
            ],
            onChanged: cubit.setPhone,
          ),
          const Sizer(height: 16),

          SignupTextField(
            label: S.current.whatsAppNumber,
            hint: S.current.whatsAppNumberHint,
            icon: Icons.chat_bubble_outline_rounded,
            keyboardType: TextInputType.text,
            textDirection: TextDirection.ltr,
            inputFormatters: [
              FilteringTextInputFormatter.allow(RegExp(r'[0-9+]')),
            ],
            onChanged: cubit.setWhatsAppNumber,
          ),
          const Sizer(height: 16),

          SignupPasswordField(
            label: S.current.password,
            controller: passwordController,
          ),
          const Sizer(height: 16),

          // ── Confirm password ───────────────────────────────
          SignupPasswordField(
            label: S.current.confirmPassword,
            controller: confirmPasswordController,
          ),
          const Sizer(height: 12),

          // ── Password rule hint ─────────────────────────────
          Container(
            padding: EdgeInsets.all(AppSizes.sm),
            decoration: BoxDecoration(
              color: ColorRes.anisCardBg,
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
              border: Border.all(color: ColorRes.anisLine),
            ),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Icon(Icons.info_outline_rounded,
                    size: 14, color: ColorRes.anisGreen),
                const Sizer(width: 6),
                Expanded(
                  child: Text(
                    S.current.strongPasswordHint,
                    style: tt.bodySmall?.copyWith(
                      color: ColorRes.anisTextMuted,
                      fontSize: 12,
                      height: 1.5,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

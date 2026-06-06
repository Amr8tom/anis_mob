import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/step_password_field_widget.dart';
import 'shared/step_password_rule_row_widget.dart';

/// Step 3 — Email + password + confirm password.
/// Password visibility toggles are view-only state (StatefulWidget justified).
/// Rule evaluation and [setEmail] calls go directly to [UserInfoCubit].
class StepEmailPasswordWidget extends StatefulWidget {
  final TextEditingController passwordController;
  final TextEditingController confirmPasswordController;

  const StepEmailPasswordWidget({
    super.key,
    required this.passwordController,
    required this.confirmPasswordController,
  });

  @override
  State<StepEmailPasswordWidget> createState() =>
      _StepEmailPasswordWidgetState();
}

class _StepEmailPasswordWidgetState extends State<StepEmailPasswordWidget> {
  bool _obscure = true;
  bool _obscureConfirm = true;

  bool _hasMinLength = false;
  bool _hasUppercase = false;
  bool _hasLowercase = false;
  bool _hasDigit = false;

  @override
  void initState() {
    super.initState();
    widget.passwordController.addListener(_evaluateRules);
  }

  void _evaluateRules() {
    final v = widget.passwordController.text;
    setState(() {
      _hasMinLength = v.length >= 6;
      _hasUppercase = v.contains(RegExp(r'[A-Z]'));
      _hasLowercase = v.contains(RegExp(r'[a-z]'));
      _hasDigit = v.contains(RegExp(r'[0-9]'));
    });
  }

  bool get _allRulesMet =>
      _hasMinLength && _hasUppercase && _hasLowercase && _hasDigit;

  @override
  void dispose() {
    widget.passwordController.removeListener(_evaluateRules);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) => p.email != c.email,
      builder: (context, state) {
        final cubit = context.read<UserInfoCubit>();

        return SingleChildScrollView(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Column(
            children: [
              const Sizer(height: 16),

              // ── Hero emoji ────────────────────────────────────────────────
              const Text('🔐', style: TextStyle(fontSize: 64)),
              const Sizer(height: 16),

              Text(
                S.current.createYourAccount,
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.bold,
                      color: ColorRes.white,
                    ),
              ),
              const Sizer(height: 6),
              Text(
                S.current.setupYourCredentials,
                style: Theme.of(context).textTheme.labelSmall?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.55),
                    ),
                textAlign: TextAlign.center,
              ),

              const Sizer(height: 32),

              // ── Email ─────────────────────────────────────────────────────
              _EmailField(
                initialValue: state.email,
                hint: S.current.emailHint,
                onChanged: cubit.setEmail,
              ),

              const Sizer(height: 16),

              // ── Password (shared atom widget) ─────────────────────────────
              StepPasswordField(
                controller: widget.passwordController,
                label: S.current.password,
                hint: S.current.passwordHint,
                obscure: _obscure,
                isValid: _allRulesMet,
                onToggle: () => setState(() => _obscure = !_obscure),
              ),

              const Sizer(height: 16),

              // ── Confirm password ──────────────────────────────────────────
              StepPasswordField(
                controller: widget.confirmPasswordController,
                label: S.current.confirmPassword,
                hint: S.current.passwordHint,
                obscure: _obscureConfirm,
                onToggle: () =>
                    setState(() => _obscureConfirm = !_obscureConfirm),
              ),

              const Sizer(height: 16),

              // ── Live rules card ───────────────────────────────────────────
              _PasswordRulesCard(
                allMet: _allRulesMet,
                hasMinLength: _hasMinLength,
                hasUppercase: _hasUppercase,
                hasLowercase: _hasLowercase,
                hasDigit: _hasDigit,
              ),

              const Sizer(height: 8),
            ],
          ),
        );
      },
    );
  }
}

// ─── Email field (single-use inside this step) ────────────────────────────────

class _EmailField extends StatelessWidget {
  final String initialValue;
  final String hint;
  final ValueChanged<String> onChanged;

  const _EmailField({
    required this.initialValue,
    required this.hint,
    required this.onChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          S.current.typeYourEmail,
          style: Theme.of(context).textTheme.labelMedium?.copyWith(
                fontWeight: FontWeight.w600,
                color: ColorRes.white.withValues(alpha: 0.70),
              ),
        ),
        const Sizer(height: 6),
        Container(
          decoration: BoxDecoration(
            color: ColorRes.anisAuthContainer,
            borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
            border: Border.all(
              color: ColorRes.anisGreen.withValues(alpha: 0.30),
              width: 1.5,
            ),
          ),
          child: TextFormField(
            initialValue: initialValue,
            keyboardType: TextInputType.emailAddress,
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  color: ColorRes.white,
                  fontWeight: FontWeight.w600,
                ),
            cursorColor: ColorRes.anisGreen,
            onChanged: onChanged,
            decoration: InputDecoration(
              hintText: hint,
              hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    color: ColorRes.white.withValues(alpha: 0.28),
                  ),
              prefixIcon: Icon(
                Icons.email_outlined,
                color: ColorRes.anisGreen.withValues(alpha: 0.80),
              ),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                borderSide: BorderSide.none,
              ),
              fillColor: ColorRes.transparent,
              filled: true,
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 16,
                vertical: 18,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

// ─── Password rules card ──────────────────────────────────────────────────────

class _PasswordRulesCard extends StatelessWidget {
  final bool allMet;
  final bool hasMinLength;
  final bool hasUppercase;
  final bool hasLowercase;
  final bool hasDigit;

  const _PasswordRulesCard({
    required this.allMet,
    required this.hasMinLength,
    required this.hasUppercase,
    required this.hasLowercase,
    required this.hasDigit,
  });

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 300),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: allMet
            ? ColorRes.anisButtonGreen.withValues(alpha: 0.08)
            : ColorRes.anisGreen.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(
          color: allMet
              ? ColorRes.anisButtonGreen.withValues(alpha: 0.35)
              : ColorRes.anisGreen.withValues(alpha: 0.20),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          StepPasswordRuleRow(label: S.current.minSixChars, met: hasMinLength),
          const Sizer(height: 6),
          StepPasswordRuleRow(
              label: S.current.minOneUppercase, met: hasUppercase),
          const Sizer(height: 6),
          StepPasswordRuleRow(
              label: S.current.minOneLowercase, met: hasLowercase),
          const Sizer(height: 6),
          StepPasswordRuleRow(label: S.current.minOneNumber, met: hasDigit),
        ],
      ),
    );
  }
}

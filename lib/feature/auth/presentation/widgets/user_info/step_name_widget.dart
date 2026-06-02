import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/step_name_field_widget.dart';

/// Step 0 — User enters their full name.
/// Logic lives in [UserInfoCubit]. This widget is a pure composition shell.
class StepNameWidget extends StatefulWidget {
  const StepNameWidget({super.key});

  @override
  State<StepNameWidget> createState() => _StepNameWidgetState();
}

// StatefulWidget is justified here ONLY to own the local TextEditingController
// and the inline error string — both are view-only state (no API / business logic).
class _StepNameWidgetState extends State<StepNameWidget> {
  late final TextEditingController _ctrl;
  String? _errorText;

  @override
  void initState() {
    super.initState();
    _ctrl = TextEditingController(
      text: context.read<UserInfoCubit>().state.name,
    );
  }

  @override
  void dispose() {
    _ctrl.dispose();
    super.dispose();
  }

  void _onChanged(String value) {
    final cubit = context.read<UserInfoCubit>();
    cubit.setName(value);
    setState(() {
      final t = value.trim();
      _errorText = t.isEmpty ? null : cubit.validateName(t);
    });
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        children: [
          Sizer(height: AppSizes.md),

          // ── Hero emoji ────────────────────────────────────────────────────
          const Text('👋', style: TextStyle(fontSize: 64)),

          Sizer(height: AppSizes.md),

          Text(
            S.current.welcomeUser,
            style: Theme.of(context).textTheme.titleLarge?.copyWith(
              fontWeight: FontWeight.bold,
              color: ColorRes.white,
            ),
          ),

          const Sizer(height: 6),

          Text(
            S.current.whatIsYourName,
            style: Theme.of(context).textTheme.labelSmall?.copyWith(
              color: ColorRes.white.withValues(alpha: 0.55),
            ),
          ),

          Sizer(height: AppSizes.xl),

          // ── Name input (extracted widget) ─────────────────────────────────
          StepNameField(
            controller: _ctrl,
            hint: S.current.typeYourNameHere,
            errorText: _errorText,
            onChanged: _onChanged,
          ),

          Sizer(height: AppSizes.md),

          // ── Hint banner ───────────────────────────────────────────────────
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
            decoration: BoxDecoration(
              color: ColorRes.anisGreen.withValues(alpha: 0.10),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              border: Border.all(
                color: ColorRes.anisGreen.withValues(alpha: 0.20),
              ),
            ),
            child: Row(
              children: [
                const Icon(
                  Icons.lightbulb_outline_rounded,
                  color: ColorRes.anisGold,
                  size: 18,
                ),
                const Sizer(width: 8),
                Expanded(
                  child: Text(
                    S.current.nameWillAppearOnProfile,
                    style: Theme.of(context).textTheme.labelSmall?.copyWith(
                      color: ColorRes.white.withValues(alpha: 0.70),
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

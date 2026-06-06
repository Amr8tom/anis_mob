import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/signup_text_field_widget.dart';

/// Step 1 — Profile: gender selection + specialization.
class StepProfileWidget extends StatelessWidget {
  const StepProfileWidget({super.key});

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
            S.current.yourProfile,
            style: tt.titleLarge?.copyWith(
              color: ColorRes.anisNavy,
              fontWeight: FontWeight.w800,
              fontSize: 20,
            ),
          ),
          const Sizer(height: 4),
          Text(
            S.current.chooseYourGender,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.anisTextMuted,
              fontSize: 13,
            ),
          ),

          const Sizer(height: 28),

          // ── Gender label ───────────────────────────────────
          Text(
            S.current.chooseYourGender,
            style: tt.labelLarge?.copyWith(
              fontWeight: FontWeight.w600,
              color: ColorRes.anisTextDark,
              fontSize: 13,
            ),
          ),
          const Sizer(height: 10),

          // ── Gender cards (light mode) ──────────────────────
          BlocBuilder<UserInfoCubit, UserInfoState>(
            buildWhen: (p, c) => p.genderId != c.genderId,
            builder: (_, state) => Row(
              children: [
                Expanded(
                  child: _LightGenderCard(
                    emoji: '👦',
                    label: S.current.male,
                    id: 1,
                    selectedId: state.genderId,
                    accentColor: ColorRes.anisGenderMale,
                    onTap: () => cubit.selectGender(1),
                  ),
                ),
                const Sizer(width: 12),
                Expanded(
                  child: _LightGenderCard(
                    emoji: '👧',
                    label: S.current.female,
                    id: 2,
                    selectedId: state.genderId,
                    accentColor: ColorRes.anisGenderFemale,
                    onTap: () => cubit.selectGender(2),
                  ),
                ),
              ],
            ),
          ),

          const Sizer(height: 28),

          // ── Specialization ─────────────────────────────────
          SignupTextField(
            label: S.current.specialization,
            hint: S.current.specializationHint,
            icon: Icons.school_outlined,
            onChanged: cubit.setSpecialization,
          ),

          const Sizer(height: 28),

          // ── All almost done banner ─────────────────────────
          Container(
            padding: EdgeInsets.all(AppSizes.md),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [ColorRes.anisGreen, ColorRes.anisButtonGreen],
                begin: Alignment.centerLeft,
                end: Alignment.centerRight,
              ),
              borderRadius: BorderRadius.circular(AppSizes.borderRadiusMd),
            ),
            child: Row(
              children: [
                const Icon(Icons.rocket_launch_rounded,
                    color: ColorRes.white, size: 22),
                const Sizer(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        S.current.almostThere,
                        style: tt.bodyMedium?.copyWith(
                          color: ColorRes.white,
                          fontWeight: FontWeight.w700,
                          fontSize: 14,
                        ),
                      ),
                      Text(
                        S.current.registerSuccessfully,
                        style: tt.bodySmall?.copyWith(
                          color: ColorRes.white.withValues(alpha: 0.78),
                          fontSize: 12,
                        ),
                      ),
                    ],
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

// ── Light-mode gender card ────────────────────────────────────────────────────

class _LightGenderCard extends StatelessWidget {
  final String emoji;
  final String label;
  final int id;
  final int? selectedId;
  final Color accentColor;
  final VoidCallback onTap;

  const _LightGenderCard({
    required this.emoji,
    required this.label,
    required this.id,
    required this.selectedId,
    required this.accentColor,
    required this.onTap,
  });

  bool get _selected => selectedId == id;

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 220),
        curve: Curves.easeOut,
        padding: EdgeInsets.symmetric(
          vertical: AppSizes.md,
          horizontal: AppSizes.sm,
        ),
        decoration: BoxDecoration(
          color: _selected
              ? accentColor.withValues(alpha: 0.10)
              : ColorRes.anisInputBg,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: _selected ? accentColor : ColorRes.anisInputBorder,
            width: _selected ? 2 : 1.5,
          ),
          boxShadow: _selected
              ? [
                  BoxShadow(
                    color: accentColor.withValues(alpha: 0.18),
                    blurRadius: 12,
                    offset: const Offset(0, 4),
                  ),
                ]
              : null,
        ),
        child: Column(
          children: [
            Text(emoji, style: const TextStyle(fontSize: 30)),
            const Sizer(height: 6),
            Text(
              label,
              style: tt.bodyMedium?.copyWith(
                fontWeight: FontWeight.w700,
                color: _selected ? accentColor : ColorRes.anisTextDark,
                fontSize: 14,
              ),
            ),
            if (_selected) ...[
              const Sizer(height: 4),
              Icon(Icons.check_circle_rounded, color: accentColor, size: 16),
            ],
          ],
        ),
      ),
    );
  }
}

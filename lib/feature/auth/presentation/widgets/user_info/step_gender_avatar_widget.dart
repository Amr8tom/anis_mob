import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:anis/common/widgets/sizeboxs/Sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../../controller/user_info/user_info_cubit.dart';
import 'shared/step_gender_card_widget.dart';
import 'shared/step_section_label_widget.dart';

/// Step 2 — Gender selection + avatar emoji picker.
/// Reads cubit state; all taps are forwarded to cubit. Zero business logic here.
class StepGenderAvatarWidget extends StatelessWidget {
  const StepGenderAvatarWidget({super.key});

  static const _avatars = [
    '🦁', '🦊', '🐯', '🦋', '🦅', '🌺',
    '🐺', '🦄', '🐉', '🌸', '🐸', '🦉',
    '🐬', '🦓', '🐘', '🦒', '🦜', '🐧',
    '🐻', '🐼', '🦊', '🐮', '🦁', '🐯',
  ];

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<UserInfoCubit, UserInfoState>(
      buildWhen: (p, c) => p.genderId != c.genderId || p.avatar != c.avatar,
      builder: (context, state) {
        final cubit = context.read<UserInfoCubit>();

        return SingleChildScrollView(
          padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Sizer(height: AppSizes.sm),

              // ── Gender ────────────────────────────────────────────────────
              StepSectionLabel(
                icon: Icons.wc_rounded,
                title: S.current.chooseYourGender,
              ),
              Sizer(height: AppSizes.sm),

              Row(
                children: [
                  Expanded(
                    child: StepGenderCard(
                      emoji: '👦',
                      label: S.current.male,
                      id: 1,
                      selectedId: state.genderId,
                      accentColor: const Color(0xFF3B82F6),
                    ),
                  ),
                  Sizer(width: AppSizes.md),
                  Expanded(
                    child: StepGenderCard(
                      emoji: '👧',
                      label: S.current.female,
                      id: 2,
                      selectedId: state.genderId,
                      accentColor: const Color(0xFFF472B6),
                    ),
                  ),
                ],
              ),

              Sizer(height: AppSizes.md),
              Divider(color: ColorRes.anisAuthBorder, thickness: 1),
              Sizer(height: AppSizes.sm),

              // ── Avatar ────────────────────────────────────────────────────
              Row(
                children: [
                  StepSectionLabel(
                    icon: Icons.face_retouching_natural,
                    title: S.current.chooseYourAvatar,
                  ),
                  const Spacer(),
                  _AvatarPreview(avatar: state.avatar),
                ],
              ),
              Sizer(height: AppSizes.sm),

              _AvatarGrid(
                avatars: _avatars,
                selected: state.avatar,
                onSelect: cubit.selectAvatar,
              ),

              Sizer(height: AppSizes.sm),
            ],
          ),
        );
      },
    );
  }
}

// ─── Avatar preview chip ──────────────────────────────────────────────────────

class _AvatarPreview extends StatelessWidget {
  final String avatar;
  const _AvatarPreview({required this.avatar});

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 300),
      width: 44.w,
      height: 44.w,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        color: ColorRes.anisAuthContainer,
        border: Border.all(
          color: avatar.isEmpty ? ColorRes.anisAuthBorder : ColorRes.anisGold,
          width: 2,
        ),
      ),
      child: Center(
        child: Text(
          avatar.isEmpty ? '?' : avatar,
          style: TextStyle(fontSize: avatar.isEmpty ? 16.sp : 22.sp),
        ),
      ),
    );
  }
}

// ─── Avatar grid ──────────────────────────────────────────────────────────────

class _AvatarGrid extends StatelessWidget {
  final List<String> avatars;
  final String selected;
  final ValueChanged<String> onSelect;

  const _AvatarGrid({
    required this.avatars,
    required this.selected,
    required this.onSelect,
  });

  @override
  Widget build(BuildContext context) {
    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: avatars.length,
      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 4,
        crossAxisSpacing: 10.w,
        mainAxisSpacing: 10.h,
        childAspectRatio: 1,
      ),
      itemBuilder: (_, i) {
        final av = avatars[i];
        final isSel = selected == av;
        return _AvatarCell(
          emoji: av,
          isSelected: isSel,
          onTap: () => onSelect(av),
        );
      },
    );
  }
}

// ─── Single avatar cell ───────────────────────────────────────────────────────

class _AvatarCell extends StatelessWidget {
  final String emoji;
  final bool isSelected;
  final VoidCallback onTap;

  const _AvatarCell({
    required this.emoji,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: isSelected
              ? ColorRes.anisGreen.withValues(alpha: 0.22)
              : ColorRes.anisAuthContainer,
          border: Border.all(
            color: isSelected ? ColorRes.anisGreen : ColorRes.anisAuthBorder,
            width: isSelected ? 2.5 : 1,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: ColorRes.anisGreen.withValues(alpha: 0.35),
                    blurRadius: 10,
                    spreadRadius: 1,
                  ),
                ]
              : null,
        ),
        child: Center(
          child: Text(
            emoji,
            style: TextStyle(
              fontSize: isSelected
                  ? AppSizes.fontSizeSm
                  : AppSizes.fontSizeSm - 2,
            ),
          ),
        ),
      ),
    );
  }
}

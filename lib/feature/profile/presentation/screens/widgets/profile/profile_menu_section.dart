import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../../language/presentation/controller/language_cubit.dart';
import '../../../controller/profile_cubit.dart';

class ProfileMenuSection extends StatelessWidget {
  const ProfileMenuSection({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        children: [
          // Privacy Policy
          _MenuTile(
            icon: Icons.lock_outline_rounded,
            label: S.current.privacyPolicy,
            onTap: () {},
          ),

          // Change Language
          BlocBuilder<LanguageCubit, LanguageState>(
            builder: (context, langState) {
              final cubit = context.read<LanguageCubit>();
              final isArabic = cubit.currentLanguage.languageCode == 'ar';
              final switchTo = isArabic ? 'EN' : 'ع';

              return _MenuTile(
                icon: Icons.language_rounded,
                label: S.current.changeLanguage,
                onTap: () => cubit.toggleLang(),
                trailingLabel: switchTo,
              );
            },
          ),

          // Logout
          _MenuTile(
            icon: Icons.logout_rounded,
            label: S.current.logout,
            onTap: () => context.read<ProfileCubit>().logout(context),
            isDestructive: true,
          ),
        ],
      ),
    );
  }
}

class _MenuTile extends StatelessWidget {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final bool isDestructive;
  final String? trailingLabel;

  const _MenuTile({
    required this.icon,
    required this.label,
    required this.onTap,
    this.isDestructive = false,
    this.trailingLabel,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    final color = isDestructive ? ColorRes.anisErrorRed : ColorRes.anisTextDark;

    return GestureDetector(
      onTap: onTap,
      behavior: HitTestBehavior.opaque,
      child: Padding(
        padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 2),
        child: Row(
          children: [
            Container(
              width: AppSizes.containerSmall * 0.67,
              height: AppSizes.containerSmall * 0.67,
              decoration: BoxDecoration(
                color: isDestructive
                    ? ColorRes.anisErrorRedBg
                    : ColorRes.anisChipBg,
                borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
              ),
              child: Icon(icon, size: AppSizes.iconSm + 4, color: color),
            ),
            const Sizer(width: 14),
            Expanded(
              child: Text(
                label,
                textAlign: TextAlign.start,
                style: tt.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w600,
                  color: color,
                ),
              ),
            ),
            if (trailingLabel != null) ...[
              const Sizer(width: 8),
              Container(
                padding: EdgeInsets.symmetric(
                  horizontal: AppSizes.sm,
                  vertical: AppSizes.xs,
                ),
                decoration: BoxDecoration(
                  color: ColorRes.anisGreen.withValues(alpha: 0.08),
                  borderRadius:
                      BorderRadius.circular(AppSizes.borderRadiusMd),
                  border: Border.all(
                    color: ColorRes.anisGreen.withValues(alpha: 0.25),
                    width: 1,
                  ),
                ),
                child: Text(
                  trailingLabel!,
                  style: tt.bodySmall?.copyWith(
                    fontWeight: FontWeight.w700,
                    color: ColorRes.anisGreen,
                  ),
                ),
              ),
            ] else
              Icon(
                Icons.chevron_left_rounded,
                size: AppSizes.iconSm,
                color: ColorRes.anisHintText,
              ),
          ],
        ),
      ),
    );
  }
}

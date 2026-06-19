import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../generated/l10n.dart';
import '../../../../../language/presentation/controller/language_cubit.dart';
import '../../../../../navigation/presentation/widgets/show_logout_dialog.dart';

import 'menu_tile.dart';

class ProfileMenuSection extends StatelessWidget {
  const ProfileMenuSection({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        children: [
          // Privacy Policy
          MenuTile(
            icon: Icons.lock_outline_rounded,
            label: S.current.privacyPolicy,
            onTap: () {},
          ),

          // Change Language
          BlocBuilder<LanguageCubit, LanguageState>(
            builder: (context, langState) {
              final cubit = context.read<LanguageCubit>();
              final langCode = cubit.currentLanguage.languageCode;
              String switchTo;
              if (langCode == 'ar') {
                switchTo = 'EN';
              } else if (langCode == 'en') {
                switchTo = 'TR';
              } else {
                switchTo = 'ع';
              }

              return MenuTile(
                icon: Icons.language_rounded,
                label: S.current.changeLanguage,
                onTap: () => cubit.toggleLang(),
                trailingLabel: switchTo,
              );
            },
          ),

          // Logout
          MenuTile(
            icon: Icons.logout_rounded,
            label: S.current.logout,
            onTap: () => showLogoutDialog(context),
            isDestructive: true,
          ),
        ],
      ),
    );
  }
}

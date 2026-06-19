import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../generated/l10n.dart';
import '../../../../core/constants/asset_resoures.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/routing/route_names.dart';
import '../controller/language_cubit.dart';
import 'custom_lang_widget.dart';

class SelectLanguageBody extends StatelessWidget {
  const SelectLanguageBody({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = context.read<LanguageCubit>();
    
    return BlocBuilder<LanguageCubit, LanguageState>(
      builder: (context, state) {
        return Padding(
          padding: EdgeInsets.all(AppSizes.ld),
          child: Column(
            children: [
              Expanded(
                child: CustomLangWidget(
                  imagPath: AssetRes.ar,
                  langName: S.current.languageArabic,
                  hight: AppSizes.xxl * 8,
                  onTab: () {
                    controller.changeLanguage("ar");
                    Navigator.popAndPushNamed(
                        context, DRoutesName.navigationMenuRoute);
                  },
                ),
              ),
              SizedBox(height: AppSizes.ld),
              Expanded(
                child: CustomLangWidget(
                  imagPath: AssetRes.en,
                  langName: S.current.languageEnglish,
                  isSvg: true,
                  hight: AppSizes.xxl * 8,
                  onTab: () {
                    controller.changeLanguage("en");
                    Navigator.popAndPushNamed(
                        context, DRoutesName.navigationMenuRoute);
                  },
                ),
              ),
              SizedBox(height: AppSizes.ld),
              Expanded(
                child: CustomLangWidget(
                  imagPath: AssetRes.tr,
                  langName: S.current.languageTurkish,
                  hight: AppSizes.xxl * 8,
                  onTab: () {
                    controller.changeLanguage("tr");
                    Navigator.popAndPushNamed(
                        context, DRoutesName.navigationMenuRoute);
                  },
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

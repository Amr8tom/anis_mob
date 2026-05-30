import 'package:bloc/bloc.dart';
import 'package:flutter/material.dart';

import '../../../../core/local_storage/cache_helper.dart';
import '../../../../core/local_storage/cache_keys.dart';

part 'language_state.dart';

class LanguageCubit extends Cubit<LanguageState> {
  LanguageCubit() : super(LanguageLoading());
  late String storedLang;
  Locale currentLanguage = Locale("ar");
  String? changedLang = CacheHelper.getString(key: CacheKeys.lang);
  String showLang = "AR";

  // Getter for current language locale
  Locale get currentLang => currentLanguage;

  Future<void> init() async {
    emit(LanguageLoading());
    storedLang = (changedLang == '' ? "ar" : changedLang)!;
    currentLanguage = Locale(storedLang);
    emit(LanguageSuccess());
  }

  void changeLanguage(String lang) {
    emit(LanguageLoading());
    currentLanguage = Locale(lang);
    CacheHelper.putString(key: CacheKeys.lang, value: lang);
    print(currentLanguage);

    emit(LanguageSuccess());
  }

  void toggleLang() {
    emit(LanguageLoading());
    if (currentLanguage == Locale("en")) {
      currentLanguage = Locale("ar");
      showLang = "EN";
      CacheHelper.putString(key: CacheKeys.lang, value: "ar");
    } else {
      currentLanguage = Locale("en");
      showLang = "AR";
      CacheHelper.putString(key: CacheKeys.lang, value: "en");
    }
    print(currentLanguage);

    emit(LanguageSuccess());
  }
}

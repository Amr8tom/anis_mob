import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../domain/use_cases/cache_language_use_case.dart';
import '../../domain/use_cases/get_language_use_case.dart';

part 'language_state.dart';

class LanguageCubit extends Cubit<LanguageState> {
  final GetLanguageUseCase _getLanguageUseCase;
  final CacheLanguageUseCase _cacheLanguageUseCase;

  LanguageCubit(this._getLanguageUseCase, this._cacheLanguageUseCase)
      : super(LanguageLoading());

  late String storedLang;
  Locale currentLanguage = const Locale("ar");
  String showLang = "AR";

  // Getter for current language locale
  Locale get currentLang => currentLanguage;

  Future<void> init() async {
    emit(LanguageLoading());
    final result = await _getLanguageUseCase.call();
    storedLang = result.getOrElse(() => "ar");
    currentLanguage = Locale(storedLang);
    
    if (storedLang == 'en') {
      showLang = 'EN';
    } else if (storedLang == 'tr') {
      showLang = 'TR';
    } else {
      showLang = 'AR';
    }
    
    emit(LanguageSuccess());
  }

  Future<void> changeLanguage(String lang) async {
    emit(LanguageLoading());
    currentLanguage = Locale(lang);
    await _cacheLanguageUseCase.call(lang);
    emit(LanguageSuccess());
  }

  void toggleLang() {
    if (currentLanguage == const Locale("ar")) {
      showLang = "EN";
      changeLanguage("en");
    } else if (currentLanguage == const Locale("en")) {
      showLang = "TR";
      changeLanguage("tr");
    } else {
      showLang = "AR";
      changeLanguage("ar");
    }
  }
}

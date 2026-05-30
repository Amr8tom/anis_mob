part of 'language_cubit.dart';

@immutable
sealed class LanguageState {}

final class LanguageLoading extends LanguageState {}

final class LanguageSuccess extends LanguageState {}

final class LanguageFailure extends LanguageState {}

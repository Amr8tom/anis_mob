import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';

abstract class LanguageRepository {
  Future<Either<Failure, String>> getLanguage();

  Future<Either<Failure, void>> cacheLanguage(String code);
}

import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/language_repository.dart';

class CacheLanguageUseCase {
  final LanguageRepository repository;

  const CacheLanguageUseCase(this.repository);

  Future<Either<Failure, void>> call(String code) {
    return repository.cacheLanguage(code);
  }
}

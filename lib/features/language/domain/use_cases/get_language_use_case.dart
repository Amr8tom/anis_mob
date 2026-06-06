import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/language_repository.dart';

class GetLanguageUseCase {
  final LanguageRepository repository;

  const GetLanguageUseCase(this.repository);

  Future<Either<Failure, String>> call() => repository.getLanguage();
}

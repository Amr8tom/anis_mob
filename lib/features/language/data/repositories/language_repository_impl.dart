import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/repositories/language_repository.dart';
import '../data_sources/language_local_data_source.dart';

class LanguageRepositoryImpl implements LanguageRepository {
  final LanguageLocalDataSource localDataSource;

  const LanguageRepositoryImpl(this.localDataSource);

  @override
  Future<Either<Failure, String>> getLanguage() async {
    try {
      return Right(await localDataSource.getLanguage());
    } catch (_) {
      return Left(CacheFailure());
    }
  }

  @override
  Future<Either<Failure, void>> cacheLanguage(String code) async {
    try {
      await localDataSource.cacheLanguage(code);
      return const Right(null);
    } catch (_) {
      return Left(CacheFailure());
    }
  }
}

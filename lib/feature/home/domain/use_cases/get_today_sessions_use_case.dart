import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entity/study_session_entity.dart';
import '../repository/home_repository.dart';

class GetTodaySessionsUseCase
    extends UseCase<List<StudySessionEntity>, NoParams> {
  final HomeRepository repository;

  GetTodaySessionsUseCase(this.repository);

  @override
  Future<Either<Failure, List<StudySessionEntity>>> call(
      {required NoParams params}) {
    return repository.getTodaySessions();
  }
}

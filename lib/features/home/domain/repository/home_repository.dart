import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/study_session_entity.dart';
import '../entity/user_profile_entity.dart';

abstract class HomeRepository {
  Future<Either<Failure, UserProfileEntity>> getUserProfile();
  Future<Either<Failure, List<StudySessionEntity>>> getTodaySessions();
}

import 'package:dartz/dartz.dart';
import '../../../../core/error/failure.dart';
import '../entity/buddy_session_entity.dart';

abstract class BuddyRepository {
  Future<Either<Failure, List<BuddySessionEntity>>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  });
  Future<Either<Failure, bool>> joinSession(String sessionId);
  Future<Either<Failure, bool>> createSession(Map<String, dynamic> data);
}

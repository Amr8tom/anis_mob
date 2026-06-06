import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repository/buddy_repository.dart';

class JoinBuddySessionUseCase {
  final BuddyRepository repository;

  const JoinBuddySessionUseCase(this.repository);

  Future<Either<Failure, bool>> call(String sessionId) {
    return repository.joinSession(sessionId);
  }
}

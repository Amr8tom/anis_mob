import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/buddy_session_entity.dart';
import '../repository/buddy_repository.dart';

class GetBuddySessionDetailsUseCase {
  final BuddyRepository repository;

  const GetBuddySessionDetailsUseCase(this.repository);

  Future<Either<Failure, BuddySessionEntity>> call(String sessionId) {
    return repository.getBuddySessionDetails(sessionId);
  }
}

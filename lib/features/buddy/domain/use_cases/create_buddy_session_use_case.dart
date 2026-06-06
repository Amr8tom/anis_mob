import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repository/buddy_repository.dart';

class CreateBuddySessionUseCase {
  final BuddyRepository repository;

  const CreateBuddySessionUseCase(this.repository);

  Future<Either<Failure, bool>> call(Map<String, dynamic> data) {
    return repository.createSession(data);
  }
}

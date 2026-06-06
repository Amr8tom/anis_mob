import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/profile_entity.dart';
import '../repository/profile_repository.dart';

class GetProfileUseCase {
  final ProfileRepository repository;
  GetProfileUseCase(this.repository);

  Future<Either<Failure, ProfileEntity>> call() => repository.getProfile();
}

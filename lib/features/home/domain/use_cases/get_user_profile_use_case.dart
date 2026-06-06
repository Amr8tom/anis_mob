import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entity/user_profile_entity.dart';
import '../repository/home_repository.dart';

class GetUserProfileUseCase extends UseCase<UserProfileEntity, NoParams> {
  final HomeRepository repository;

  GetUserProfileUseCase(this.repository);

  @override
  Future<Either<Failure, UserProfileEntity>> call({required NoParams params}) {
    return repository.getUserProfile();
  }
}

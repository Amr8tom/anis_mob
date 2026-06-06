import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/auth_repository.dart';

class GetAuthTokenUseCase {
  final AuthRepository repository;

  const GetAuthTokenUseCase(this.repository);

  Future<Either<Failure, String?>> call() => repository.getToken();
}

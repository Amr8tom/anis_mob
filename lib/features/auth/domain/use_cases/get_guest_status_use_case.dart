import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/auth_repository.dart';

class GetGuestStatusUseCase {
  final AuthRepository repository;

  const GetGuestStatusUseCase(this.repository);

  Future<Either<Failure, bool>> call() => repository.isGuest();
}

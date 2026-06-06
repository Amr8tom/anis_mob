import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/auth_repository.dart';

class SignOutUseCase {
  final AuthRepository repository;

  const SignOutUseCase(this.repository);

  Future<Either<Failure, void>> call() => repository.signOut();
}

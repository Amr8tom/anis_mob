import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../repositories/auth_repository.dart';

/// Persists the guest session flag via the auth repository.
/// No params — guest login is always local-only.
class GuestLoginUseCase {
  final AuthRepository _repository;

  const GuestLoginUseCase(this._repository);

  Future<Either<Failure, void>> call() => _repository.loginAsGuest();
}

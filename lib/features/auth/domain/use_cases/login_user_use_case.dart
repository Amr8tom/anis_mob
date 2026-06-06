import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entities/login.dart';
import '../repositories/auth_repository.dart';

class LoginUserUseCase extends UseCase<Login, LoginUserParams> {
  final AuthRepository _repository;
  LoginUserUseCase(this._repository);

  @override
  Future<Either<Failure, Login>> call({required LoginUserParams params}) async {
    return await _repository.loginUser(params: params);
  }
}

class LoginUserParams extends Equatable {
  final String phoneNumber;
  final String password;
  const LoginUserParams({
    required this.phoneNumber,
    required this.password,
  });

  Map<String, dynamic> toMap() => {
        'phone_number': phoneNumber,
        'password': password,
      };

  @override
  List<Object?> get props => [phoneNumber, password];
}

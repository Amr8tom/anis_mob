import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entities/create_user_info.dart';
import '../repositories/auth_repository.dart';

class CreateUserUseCase extends UseCase<CreateUserInfo, CreateUserInfoParams> {
  final AuthRepository _repository;
  CreateUserUseCase(this._repository);

  @override
  Future<Either<Failure, CreateUserInfo>> call({
    required CreateUserInfoParams params,
  }) async {
    return await _repository.createUserInfo(params: params);
  }
}

class CreateUserInfoParams extends Equatable {
  final String fullName;
  final String phoneNumber;
  final String whatsAppNumber;
  final String password;
  final String passwordConfirmation;

  const CreateUserInfoParams({
    required this.fullName,
    required this.phoneNumber,
    required this.whatsAppNumber,
    required this.password,
    required this.passwordConfirmation,
  });

  Map<String, dynamic> toMap() => {
        'full_name': fullName,
        'phone_number': phoneNumber,
        'whatsapp_number': whatsAppNumber,
        'password': password,
        'password_confirmation': passwordConfirmation,
      };

  @override
  List<Object?> get props => [
        fullName,
        phoneNumber,
        whatsAppNumber,
        password,
        passwordConfirmation,
      ];
}

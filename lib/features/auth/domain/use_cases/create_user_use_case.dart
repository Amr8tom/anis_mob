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
  final String? password;
  final String? phoneNumber;
  final String? whatsAppNumber;

  const CreateUserInfoParams({
    required this.fullName,
    this.password,
    this.phoneNumber,
    this.whatsAppNumber,
  });

  Map<String, dynamic> toMap() => {
        'full_name': fullName,
        'phone_number': phoneNumber,
        'whatsapp_number': whatsAppNumber,
        'password': password,
      };

  @override
  List<Object?> get props => [
        fullName,
        password,
        phoneNumber,
        whatsAppNumber,
      ];
}

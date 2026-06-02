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
  Future<Either<Failure, CreateUserInfo>> call({required CreateUserInfoParams params}) async {
    return await _repository.createUserInfo(params: params);
  }
}

class CreateUserInfoParams extends Equatable {
  final String fullName;
  final String? userName;
  final String? email;
  final String? password;
  final int? roleId;
  final int? genderId;
  final String? university;
  final String? studyMajor;
  final String? yearOfStudy;

  const CreateUserInfoParams({
    required this.fullName,
    this.userName,
    this.email,
    this.password,
    this.roleId,
    this.genderId,
    this.university,
    this.studyMajor,
    this.yearOfStudy,
  });

  Map<String, dynamic> toMap() => {
    'fullName': fullName,
    'userName': userName ?? fullName,
    'email': email ?? '${fullName.replaceAll(' ', '')}@anis.app',
    'password': password,
    'roleId': roleId ?? 1,
    'genderId': genderId,
    'university': university,
    'studyMajor': studyMajor,
    'yearOfStudy': yearOfStudy,
    'selectedInterestIds': [0],
    'phoneNumber': '0000000000',
  };

  @override
  List<Object?> get props => [fullName, userName, email, password, roleId, genderId, university, studyMajor, yearOfStudy];
}

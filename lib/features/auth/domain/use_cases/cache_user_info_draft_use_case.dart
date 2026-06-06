import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../repositories/auth_repository.dart';

class CacheUserInfoDraftUseCase {
  final AuthRepository repository;

  const CacheUserInfoDraftUseCase(this.repository);

  Future<Either<Failure, void>> call(CacheUserInfoDraftParams params) {
    return repository.cacheUserInfoDraft(
      name: params.name,
      email: params.email,
      whatsAppNumber: params.whatsAppNumber,
      gender: params.gender,
      avatar: params.avatar,
      university: params.university,
      major: params.major,
      year: params.year,
    );
  }
}

class CacheUserInfoDraftParams extends Equatable {
  final String name;
  final String email;
  final String whatsAppNumber;
  final String gender;
  final String avatar;
  final String university;
  final String major;
  final String year;

  const CacheUserInfoDraftParams({
    required this.name,
    required this.email,
    required this.whatsAppNumber,
    required this.gender,
    required this.avatar,
    required this.university,
    required this.major,
    required this.year,
  });

  @override
  List<Object?> get props => [
        name,
        email,
        whatsAppNumber,
        gender,
        avatar,
        university,
        major,
        year,
      ];
}

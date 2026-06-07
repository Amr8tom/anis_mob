import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../entity/profile_entity.dart';
import '../repository/profile_repository.dart';

class UpdateProfileUseCase {
  final ProfileRepository repository;

  const UpdateProfileUseCase(this.repository);

  Future<Either<Failure, ProfileEntity>> call(UpdateProfileParams params) {
    return repository.updateProfile(params);
  }
}

class UpdateProfileParams extends Equatable {
  final String? email;
  final String? university;
  final String? studyField;
  final String? gender;
  final List<String>? interests;

  const UpdateProfileParams({
    this.email,
    this.university,
    this.studyField,
    this.gender,
    this.interests,
  });

  Map<String, dynamic> toMap() => {
        if (email != null && email!.trim().isNotEmpty) 'email': email!.trim(),
        if (university != null && university!.trim().isNotEmpty)
          'university': university!.trim(),
        if (studyField != null && studyField!.trim().isNotEmpty)
          'study_field': studyField!.trim(),
        if (gender != null && gender!.isNotEmpty) 'gender': gender,
        if (interests != null && interests!.isNotEmpty) 'interests': interests,
      };

  @override
  List<Object?> get props => [email, university, studyField, gender, interests];
}

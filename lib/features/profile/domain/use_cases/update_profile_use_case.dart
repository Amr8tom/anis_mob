import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';
import 'package:image_picker/image_picker.dart';

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
  /// When non-null the data layer will upload this as multipart/form-data.
  final XFile? avatarFile;

  const UpdateProfileParams({
    this.email,
    this.university,
    this.studyField,
    this.gender,
    this.interests,
    this.avatarFile,
  });

  /// Plain-text fields only — used when there is no avatar to upload.
  Map<String, dynamic> toMap() => {
        if (email != null && email!.trim().isNotEmpty) 'email': email!.trim(),
        if (university != null && university!.trim().isNotEmpty)
          'university': university!.trim(),
        if (studyField != null && studyField!.trim().isNotEmpty)
          'study_field': studyField!.trim(),
        if (gender != null && gender!.isNotEmpty) 'gender': gender,
        if (interests != null && interests!.isNotEmpty) 'interests': interests,
      };

  bool get hasAvatar => avatarFile != null;

  @override
  List<Object?> get props =>
      [email, university, studyField, gender, interests, avatarFile?.path];
}

import 'package:equatable/equatable.dart';
import 'package:flutter/foundation.dart'; // For @immutable in state
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../generated/l10n.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/use_cases/get_profile_use_case.dart';
import '../../domain/use_cases/update_profile_use_case.dart';

part 'profile_completion_state.dart';

class ProfileCompletionCubit extends Cubit<ProfileCompletionState> {
  final GetProfileUseCase getProfileUseCase;
  final UpdateProfileUseCase updateProfileUseCase;

  ProfileCompletionCubit({
    required this.getProfileUseCase,
    required this.updateProfileUseCase,
  }) : super(const ProfileCompletionState()) {
    loadProfile();
  }

  Future<void> loadProfile() async {
    emit(state.copyWith(status: ProfileCompletionStatus.loading));
    final result = await getProfileUseCase();

    result.fold(
      (failure) => emit(state.copyWith(
        status: ProfileCompletionStatus.ready,
        errorMessage: failure.message,
      )),
      (profile) {
        emit(state.copyWith(
          status: ProfileCompletionStatus.ready,
          profile: profile,
          gender: profile.gender,
          interests: profile.interests,
          clearError: true,
        ));
      },
    );
  }

  void selectGender(String gender) => emit(state.copyWith(gender: gender));

  void addSuggestedInterest(String interest) {
    if (state.interests.contains(interest) || state.interests.length >= 10) {
      return;
    }
    emit(state.copyWith(interests: [...state.interests, interest]));
  }

  void removeInterest(String interest) {
    emit(state.copyWith(
      interests: state.interests.where((item) => item != interest).toList(),
    ));
  }

  Future<void> saveProfile({
    required String email,
    required String university,
    required String studyField,
  }) async {
    if (state.status == ProfileCompletionStatus.saving) return;
    if (state.gender.isEmpty) {
      emit(state.copyWith(errorMessage: S.current.selectGenderError));
      return;
    }
    if (state.interests.isEmpty) {
      emit(state.copyWith(errorMessage: S.current.addInterestError));
      return;
    }

    emit(state.copyWith(
      status: ProfileCompletionStatus.saving,
      clearError: true,
    ));

    final result = await updateProfileUseCase(
      UpdateProfileParams(
        email: email,
        university: university,
        studyField: studyField,
        gender: state.gender,
        interests: state.interests,
      ),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        status: ProfileCompletionStatus.ready,
        errorMessage: failure.message ?? S.current.generalError,
      )),
      (profile) => emit(state.copyWith(
        status: ProfileCompletionStatus.success,
        profile: profile,
        clearError: true,
      )),
    );
  }

  Future<void> saveDraftAndContinue({
    required String email,
    required String university,
    required String studyField,
  }) async {
    if (state.status == ProfileCompletionStatus.saving) return;

    final trimmedEmail = email.trim();
    final params = UpdateProfileParams(
      email: trimmedEmail.contains('@') ? trimmedEmail : null,
      university: _nonEmpty(university),
      studyField: _nonEmpty(studyField),
      gender: _nonEmpty(state.gender),
      interests: state.interests.isEmpty ? null : state.interests,
    );

    if (params.toMap().isEmpty) {
      emit(state.copyWith(status: ProfileCompletionStatus.skipped));
      return;
    }

    emit(state.copyWith(
      status: ProfileCompletionStatus.saving,
      clearError: true,
    ));
    final result = await updateProfileUseCase(params);
    result.fold(
      (_) => emit(state.copyWith(status: ProfileCompletionStatus.skipped)),
      (profile) => emit(state.copyWith(
        status: ProfileCompletionStatus.skipped,
        profile: profile,
      )),
    );
  }

  String? _nonEmpty(String value) {
    final trimmed = value.trim();
    return trimmed.isEmpty ? null : trimmed;
  }

  void clearError() => emit(state.copyWith(clearError: true));
}

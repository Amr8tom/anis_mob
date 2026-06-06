import 'package:equatable/equatable.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../domain/entity/profile_entity.dart';
import '../../domain/use_cases/get_profile_use_case.dart';

part 'profile_state.dart';

class ProfileCubit extends Cubit<ProfileState> {
  final GetProfileUseCase getProfileUseCase;
  final ImagePicker _picker = ImagePicker();

  ProfileCubit({required this.getProfileUseCase})
      : super(const ProfileState()) {
    loadProfile();
  }

  // ── Data ──────────────────────────────────────────────────────────────────

  Future<void> loadProfile() async {
    emit(state.copyWith(status: ProfileStatus.loading));
    final result = await getProfileUseCase();
    result.fold(
      (failure) => emit(state.copyWith(
        status: ProfileStatus.failure,
        errorMessage: failure.message,
      )),
      (profile) => emit(state.copyWith(
        status: ProfileStatus.success,
        profile: profile,
      )),
    );
  }

  Future<void> refresh() => loadProfile();

  // ── Avatar ────────────────────────────────────────────────────────────────

  Future<void> pickAvatar() async {
    final picked = await _picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 85,
      maxWidth: 512,
      maxHeight: 512,
    );
    if (picked != null) {
      emit(state.copyWith(avatarPath: picked.path));
    }
  }
}

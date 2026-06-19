import 'package:equatable/equatable.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../auth/domain/use_cases/get_guest_status_use_case.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/use_cases/get_profile_use_case.dart';
import '../../domain/use_cases/update_profile_use_case.dart';

part 'profile_state.dart';

class ProfileCubit extends Cubit<ProfileState> {
  final GetProfileUseCase getProfileUseCase;
  final UpdateProfileUseCase updateProfileUseCase;
  final GetGuestStatusUseCase getGuestStatusUseCase;
  final ImagePicker _picker = ImagePicker();

  ProfileCubit({
    required this.getProfileUseCase,
    required this.updateProfileUseCase,
    required this.getGuestStatusUseCase,
  }) : super(const ProfileState()) {
    loadProfile();
  }

  // ── Data ──────────────────────────────────────────────────────────────────

  Future<void> loadProfile() async {
    emit(state.copyWith(status: ProfileStatus.loading));
    final guestResult = await getGuestStatusUseCase.call();
    final isGuest = guestResult.getOrElse(() => false);
    if (isGuest) {
      emit(state.copyWith(
        status: ProfileStatus.success,
        isGuest: true,
      ));
      return;
    }

    final result = await getProfileUseCase();
    result.fold(
      (failure) => emit(state.copyWith(
        status: ProfileStatus.failure,
        errorMessage: failure.message,
      )),
      (profile) => emit(state.copyWith(
        status: ProfileStatus.success,
        profile: profile,
        isGuest: false,
      )),
    );
  }

  Future<void> refresh() => loadProfile();

  // ── Avatar ────────────────────────────────────────────────────────────────

  /// Opens the gallery, shows a preview immediately, then uploads to the server.
  /// [avatarUploadError] in state is set on failure so the UI can show a snackbar.
  Future<void> pickAvatar() async {
    final picked = await _picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 85,
      maxWidth: 512,
      maxHeight: 512,
    );
    if (picked == null) return;

    // 1. Show the image locally right away (optimistic preview).
    emit(state.copyWith(avatarPath: picked.path, avatarUploading: true));

    // 2. Upload to the server.
    final result = await updateProfileUseCase.call(
      UpdateProfileParams(avatarFile: picked),
    );

    result.fold(
      (failure) {
        // Upload failed — keep the local preview but signal the error.
        emit(state.copyWith(
          avatarUploading: false,
          avatarUploadError: failure.message,
        ));
      },
      (updatedProfile) {
        // Server returned the updated profile (with the new avatarPath URL).
        emit(state.copyWith(
          avatarUploading: false,
          profile: updatedProfile,
          clearAvatarError: true,
        ));
      },
    );
  }

  void clearAvatarError() => emit(state.copyWith(clearAvatarError: true));
}

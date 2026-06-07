import 'package:equatable/equatable.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../auth/domain/use_cases/get_guest_status_use_case.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/use_cases/get_profile_use_case.dart';

part 'profile_state.dart';

class ProfileCubit extends Cubit<ProfileState> {
  final GetProfileUseCase getProfileUseCase;
  final GetGuestStatusUseCase getGuestStatusUseCase;
  final ImagePicker _picker = ImagePicker();

  ProfileCubit({
    required this.getProfileUseCase,
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

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
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

  // ── Navigation ────────────────────────────────────────────────────────────

  void navigateToPlans(BuildContext context) {
    context.pushNamed(
      DRoutesName.plansRoute,
      arguments: {'currentPlan': state.profile?.subscriptionType ?? 'free'},
    );
  }

  void logout(BuildContext context) {
    // TODO: clear session/cache then push login
    context.pushNamedAndRemoveUntil(
      DRoutesName.loginRoute,
      predicate: (_) => false,
    );
  }
}

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

import '../../domain/entity/profile_entity.dart';
import '../../domain/use_cases/get_profile_use_case.dart';

part 'profile_state.dart';

class ProfileCubit extends Cubit<ProfileState> {
  final GetProfileUseCase getProfileUseCase;

  ProfileCubit({required this.getProfileUseCase})
      : super(const ProfileState()) {
    loadProfile();
  }

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
}

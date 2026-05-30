import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/utils/usecases/base_usecase.dart';
import '../../domain/entity/study_session_entity.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../../domain/use_cases/get_today_sessions_use_case.dart';
import '../../domain/use_cases/get_user_profile_use_case.dart';

part 'home_state.dart';

class HomeCubit extends Cubit<HomeState> {
  final GetUserProfileUseCase getUserProfileUseCase;
  final GetTodaySessionsUseCase getTodaySessionsUseCase;

  HomeCubit({
    required this.getUserProfileUseCase,
    required this.getTodaySessionsUseCase,
  }) : super(const HomeState()) {
    loadHomeData();
  }

  Future<void> loadHomeData() async {
    emit(state.copyWith(status: HomeStatus.loading));

    // Run both requests in parallel
    final results = await Future.wait([
      getUserProfileUseCase(params: NoParams()),
      getTodaySessionsUseCase(params: NoParams()),
    ]);

    final profileResult = results[0];
    final sessionsResult = results[1];

    UserProfileEntity? profile;
    List<StudySessionEntity> sessions = [];
    String? error;

    profileResult.fold(
      (failure) => error = failure.message,
      (data) => profile = data as UserProfileEntity,
    );

    sessionsResult.fold(
      (failure) => error ??= failure.message,
      (data) => sessions = data as List<StudySessionEntity>,
    );

    if (profile != null) {
      emit(state.copyWith(
        status: HomeStatus.success,
        userProfile: profile,
        todaySessions: sessions,
        errorMessage: null,
      ));
    } else {
      emit(state.copyWith(
        status: HomeStatus.failure,
        errorMessage: error ?? 'حدث خطأ ما',
      ));
    }
  }

  Future<void> refresh() => loadHomeData();
}

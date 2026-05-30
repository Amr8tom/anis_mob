import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/use_cases/get_buddy_sessions_use_case.dart';

part 'buddy_state.dart';

class BuddyCubit extends Cubit<BuddyState> {
  final GetBuddySessionsUseCase getBuddySessionsUseCase;

  BuddyCubit({required this.getBuddySessionsUseCase})
      : super(const BuddyState()) {
    loadSessions();
  }

  Future<void> loadSessions() async {
    emit(state.copyWith(status: BuddyStatus.loading));

    final result = await getBuddySessionsUseCase(
      params: BuddySessionsParams(
        university: state.universityFilter,
        subject: state.subjectFilter,
        filter: state.activeChip,
      ),
    );

    result.fold(
      (failure) => emit(state.copyWith(
        status: BuddyStatus.failure,
        errorMessage: failure.message,
      )),
      (sessions) => emit(state.copyWith(
        status: BuddyStatus.success,
        sessions: sessions,
        errorMessage: null,
      )),
    );
  }

  void setUniversityFilter(String value) {
    emit(state.copyWith(universityFilter: value));
    loadSessions();
  }

  void setSubjectFilter(String value) {
    emit(state.copyWith(subjectFilter: value));
    loadSessions();
  }

  void setActiveChip(String chip) {
    emit(state.copyWith(activeChip: chip));
    loadSessions();
  }

  Future<void> refresh() => loadSessions();
}

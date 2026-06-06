import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/use_cases/get_buddy_session_details_use_case.dart';

part 'buddy_session_details_state.dart';

class BuddySessionDetailsCubit extends Cubit<BuddySessionDetailsState> {
  final GetBuddySessionDetailsUseCase getBuddySessionDetailsUseCase;
  final String sessionId;

  BuddySessionDetailsCubit({
    required this.getBuddySessionDetailsUseCase,
    required this.sessionId,
  }) : super(const BuddySessionDetailsState()) {
    loadSession();
  }

  Future<void> loadSession() async {
    emit(state.copyWith(status: BuddySessionDetailsStatus.loading));

    final result = await getBuddySessionDetailsUseCase(sessionId);
    result.fold(
      (failure) => emit(
        state.copyWith(
          status: BuddySessionDetailsStatus.failure,
          errorMessage: failure.message,
        ),
      ),
      (session) => emit(
        state.copyWith(
          status: BuddySessionDetailsStatus.success,
          session: session,
          clearErrorMessage: true,
        ),
      ),
    );
  }
}

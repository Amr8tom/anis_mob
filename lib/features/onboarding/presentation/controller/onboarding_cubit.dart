import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/utils/enums/general_status.dart';
import '../../domain/use_cases/complete_onboarding_use_case.dart';

part 'onboarding_state.dart';

class OnboardingCubit extends Cubit<OnboardingState> {
  final CompleteOnboardingUseCase _completeOnboardingUseCase;

  OnboardingCubit(this._completeOnboardingUseCase)
      : super(const OnboardingState());

  Future<void> completeOnboarding() async {
    if (state.status.isLoading) return;

    emit(state.copyWith(status: GeneralStatus.loading));
    final result = await _completeOnboardingUseCase.call();

    result.fold(
      (_) => emit(state.copyWith(status: GeneralStatus.error)),
      (_) => emit(state.copyWith(status: GeneralStatus.success)),
    );
  }
}

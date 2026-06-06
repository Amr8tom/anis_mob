part of 'onboarding_cubit.dart';

final class OnboardingState extends Equatable {
  final GeneralStatus status;

  const OnboardingState({
    this.status = GeneralStatus.initialized,
  });

  OnboardingState copyWith({
    GeneralStatus? status,
  }) {
    return OnboardingState(
      status: status ?? this.status,
    );
  }

  @override
  List<Object?> get props => [status];
}

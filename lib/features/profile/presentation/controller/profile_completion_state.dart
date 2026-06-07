part of 'profile_completion_cubit.dart';

enum ProfileCompletionStatus { initial, loading, ready, saving, success, skipped }

@immutable
final class ProfileCompletionState extends Equatable {
  final ProfileCompletionStatus status;
  final ProfileEntity? profile;
  final String gender;
  final List<String> interests;
  final String? errorMessage;

  const ProfileCompletionState({
    this.status = ProfileCompletionStatus.initial,
    this.profile,
    this.gender = '',
    this.interests = const [],
    this.errorMessage,
  });

  ProfileCompletionState copyWith({
    ProfileCompletionStatus? status,
    ProfileEntity? profile,
    String? gender,
    List<String>? interests,
    String? errorMessage,
    bool clearError = false,
  }) {
    return ProfileCompletionState(
      status: status ?? this.status,
      profile: profile ?? this.profile,
      gender: gender ?? this.gender,
      interests: interests ?? this.interests,
      errorMessage: clearError ? null : (errorMessage ?? this.errorMessage),
    );
  }

  @override
  List<Object?> get props => [status, profile, gender, interests, errorMessage];
}

part of 'profile_cubit.dart';

enum ProfileStatus { initial, loading, success, failure }

final class ProfileState extends Equatable {
  final ProfileStatus status;
  final ProfileEntity? profile;
  final String? errorMessage;
  final String? avatarPath; // locally picked image path

  const ProfileState({
    this.status = ProfileStatus.initial,
    this.profile,
    this.errorMessage,
    this.avatarPath,
  });

  ProfileState copyWith({
    ProfileStatus? status,
    ProfileEntity? profile,
    String? errorMessage,
    String? avatarPath,
    bool clearAvatar = false,
  }) {
    return ProfileState(
      status: status ?? this.status,
      profile: profile ?? this.profile,
      errorMessage: errorMessage ?? this.errorMessage,
      avatarPath: clearAvatar ? null : (avatarPath ?? this.avatarPath),
    );
  }

  @override
  List<Object?> get props => [status, profile, errorMessage, avatarPath];
}

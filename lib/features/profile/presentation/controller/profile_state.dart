part of 'profile_cubit.dart';

enum ProfileStatus { initial, loading, success, failure }

final class ProfileState extends Equatable {
  final ProfileStatus status;
  final ProfileEntity? profile;
  final String? errorMessage;
  final String? avatarPath; // locally picked image path
  final bool isGuest;

  const ProfileState({
    this.status = ProfileStatus.initial,
    this.profile,
    this.errorMessage,
    this.avatarPath,
    this.isGuest = false,
  });

  ProfileState copyWith({
    ProfileStatus? status,
    ProfileEntity? profile,
    String? errorMessage,
    String? avatarPath,
    bool? isGuest,
    bool clearAvatar = false,
  }) {
    return ProfileState(
      status: status ?? this.status,
      profile: profile ?? this.profile,
      errorMessage: errorMessage ?? this.errorMessage,
      avatarPath: clearAvatar ? null : (avatarPath ?? this.avatarPath),
      isGuest: isGuest ?? this.isGuest,
    );
  }

  @override
  List<Object?> get props =>
      [status, profile, errorMessage, avatarPath, isGuest];
}

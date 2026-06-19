part of 'profile_cubit.dart';

enum ProfileStatus { initial, loading, success, failure }

final class ProfileState extends Equatable {
  final ProfileStatus status;
  final ProfileEntity? profile;
  final String? errorMessage;
  /// Locally-picked image file path shown as an optimistic preview.
  final String? avatarPath;
  final bool isGuest;
  /// True while the avatar upload request is in-flight.
  final bool avatarUploading;
  /// Non-null when the last avatar upload failed.
  final String? avatarUploadError;

  const ProfileState({
    this.status = ProfileStatus.initial,
    this.profile,
    this.errorMessage,
    this.avatarPath,
    this.isGuest = false,
    this.avatarUploading = false,
    this.avatarUploadError,
  });

  ProfileState copyWith({
    ProfileStatus? status,
    ProfileEntity? profile,
    String? errorMessage,
    String? avatarPath,
    bool? isGuest,
    bool clearAvatar = false,
    bool? avatarUploading,
    String? avatarUploadError,
    bool clearAvatarError = false,
  }) {
    return ProfileState(
      status: status ?? this.status,
      profile: profile ?? this.profile,
      errorMessage: errorMessage ?? this.errorMessage,
      avatarPath: clearAvatar ? null : (avatarPath ?? this.avatarPath),
      isGuest: isGuest ?? this.isGuest,
      avatarUploading: avatarUploading ?? this.avatarUploading,
      avatarUploadError:
          clearAvatarError ? null : (avatarUploadError ?? this.avatarUploadError),
    );
  }

  @override
  List<Object?> get props => [
        status,
        profile,
        errorMessage,
        avatarPath,
        isGuest,
        avatarUploading,
        avatarUploadError,
      ];
}

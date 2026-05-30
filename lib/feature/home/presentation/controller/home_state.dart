part of 'home_cubit.dart';

enum HomeStatus { initial, loading, success, failure }

final class HomeState extends Equatable {
  final HomeStatus status;
  final UserProfileEntity? userProfile;
  final List<StudySessionEntity> todaySessions;
  final String? errorMessage;

  const HomeState({
    this.status = HomeStatus.initial,
    this.userProfile,
    this.todaySessions = const [],
    this.errorMessage,
  });

  HomeState copyWith({
    HomeStatus? status,
    UserProfileEntity? userProfile,
    List<StudySessionEntity>? todaySessions,
    String? errorMessage,
  }) {
    return HomeState(
      status: status ?? this.status,
      userProfile: userProfile ?? this.userProfile,
      todaySessions: todaySessions ?? this.todaySessions,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [status, userProfile, todaySessions, errorMessage];
}

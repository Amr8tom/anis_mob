part of 'buddy_cubit.dart';

enum BuddyStatus { initial, loading, success, failure }
enum BuddyActionStatus { idle, loading, success, failure }

final class BuddyState extends Equatable {
  final BuddyStatus status;
  final List<BuddySessionEntity> sessions;
  final String universityFilter;
  final String subjectFilter;
  final String activeChip; // 'all' | 'today' | 'open' | 'availableNow'
  final String? errorMessage;
  final BuddyActionStatus joinStatus;
  final BuddyActionStatus createStatus;
  final String? joinedSessionId;

  const BuddyState({
    this.status = BuddyStatus.initial,
    this.sessions = const [],
    this.universityFilter = '',
    this.subjectFilter = '',
    this.activeChip = 'all',
    this.errorMessage,
    this.joinStatus = BuddyActionStatus.idle,
    this.createStatus = BuddyActionStatus.idle,
    this.joinedSessionId,
  });

  BuddyState copyWith({
    BuddyStatus? status,
    List<BuddySessionEntity>? sessions,
    String? universityFilter,
    String? subjectFilter,
    String? activeChip,
    String? errorMessage,
    BuddyActionStatus? joinStatus,
    BuddyActionStatus? createStatus,
    String? joinedSessionId,
  }) {
    return BuddyState(
      status: status ?? this.status,
      sessions: sessions ?? this.sessions,
      universityFilter: universityFilter ?? this.universityFilter,
      subjectFilter: subjectFilter ?? this.subjectFilter,
      activeChip: activeChip ?? this.activeChip,
      errorMessage: errorMessage ?? this.errorMessage,
      joinStatus: joinStatus ?? this.joinStatus,
      createStatus: createStatus ?? this.createStatus,
      joinedSessionId: joinedSessionId ?? this.joinedSessionId,
    );
  }

  @override
  List<Object?> get props => [
        status, sessions, universityFilter, subjectFilter, activeChip,
        errorMessage, joinStatus, createStatus, joinedSessionId,
      ];
}

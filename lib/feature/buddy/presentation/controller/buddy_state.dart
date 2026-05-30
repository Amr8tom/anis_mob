part of 'buddy_cubit.dart';

enum BuddyStatus { initial, loading, success, failure }

final class BuddyState extends Equatable {
  final BuddyStatus status;
  final List<BuddySessionEntity> sessions;
  final String universityFilter;
  final String subjectFilter;
  /// 'all' | 'today' | 'thisWeek' | 'availableNow'
  final String activeChip;
  final String? errorMessage;

  const BuddyState({
    this.status = BuddyStatus.initial,
    this.sessions = const [],
    this.universityFilter = '',
    this.subjectFilter = '',
    this.activeChip = 'all',
    this.errorMessage,
  });

  BuddyState copyWith({
    BuddyStatus? status,
    List<BuddySessionEntity>? sessions,
    String? universityFilter,
    String? subjectFilter,
    String? activeChip,
    String? errorMessage,
  }) {
    return BuddyState(
      status: status ?? this.status,
      sessions: sessions ?? this.sessions,
      universityFilter: universityFilter ?? this.universityFilter,
      subjectFilter: subjectFilter ?? this.subjectFilter,
      activeChip: activeChip ?? this.activeChip,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [
        status,
        sessions,
        universityFilter,
        subjectFilter,
        activeChip,
        errorMessage,
      ];
}

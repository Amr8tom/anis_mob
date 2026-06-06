part of 'buddy_cubit.dart';

enum BuddyStatus { initial, loading, success, failure }

enum BuddyActionStatus { idle, loading, success, failure }

final class BuddyState extends Equatable {
  // ── Session list ───────────────────────────────────────
  final BuddyStatus status;
  final List<BuddySessionEntity> sessions;
  final String universityFilter;
  final String subjectFilter;
  final String activeChip;
  final String? errorMessage;
  final bool isGuest;

  // ── Join action ────────────────────────────────────────
  final BuddyActionStatus joinStatus;
  final String? joinedSessionId;

  // ── Create action ──────────────────────────────────────
  final BuddyActionStatus createStatus;

  // ── Create-session form state ──────────────────────────
  final List<WorkspaceEntity> workspaces; // available workspaces for the picker
  final BuddyStatus workspacesStatus;
  final WorkspaceEntity? selectedWorkspace; // chosen workspace on the form
  final DateTime? createStartTime;
  final int maxCapacity;
  final List<String> createRules;
  final String workspaceSearchQuery;

  const BuddyState({
    this.status = BuddyStatus.initial,
    this.sessions = const [],
    this.universityFilter = '',
    this.subjectFilter = '',
    this.activeChip = 'all',
    this.errorMessage,
    this.isGuest = false,
    this.joinStatus = BuddyActionStatus.idle,
    this.joinedSessionId,
    this.createStatus = BuddyActionStatus.idle,
    this.workspaces = const [],
    this.workspacesStatus = BuddyStatus.initial,
    this.selectedWorkspace,
    this.createStartTime,
    this.maxCapacity = 4,
    this.createRules = const [],
    this.workspaceSearchQuery = '',
  });

  BuddyState copyWith({
    BuddyStatus? status,
    List<BuddySessionEntity>? sessions,
    String? universityFilter,
    String? subjectFilter,
    String? activeChip,
    String? errorMessage,
    bool? isGuest,
    BuddyActionStatus? joinStatus,
    String? joinedSessionId,
    BuddyActionStatus? createStatus,
    List<WorkspaceEntity>? workspaces,
    BuddyStatus? workspacesStatus,
    WorkspaceEntity? selectedWorkspace,
    DateTime? createStartTime,
    int? maxCapacity,
    List<String>? createRules,
    String? workspaceSearchQuery,
    bool clearSelectedWorkspace = false,
    bool clearCreateStartTime = false,
  }) {
    return BuddyState(
      status: status ?? this.status,
      sessions: sessions ?? this.sessions,
      universityFilter: universityFilter ?? this.universityFilter,
      subjectFilter: subjectFilter ?? this.subjectFilter,
      activeChip: activeChip ?? this.activeChip,
      errorMessage: errorMessage ?? this.errorMessage,
      isGuest: isGuest ?? this.isGuest,
      joinStatus: joinStatus ?? this.joinStatus,
      joinedSessionId: joinedSessionId ?? this.joinedSessionId,
      createStatus: createStatus ?? this.createStatus,
      workspaces: workspaces ?? this.workspaces,
      workspacesStatus: workspacesStatus ?? this.workspacesStatus,
      selectedWorkspace: clearSelectedWorkspace
          ? null
          : (selectedWorkspace ?? this.selectedWorkspace),
      createStartTime: clearCreateStartTime
          ? null
          : (createStartTime ?? this.createStartTime),
      maxCapacity: maxCapacity ?? this.maxCapacity,
      createRules: createRules ?? this.createRules,
      workspaceSearchQuery: workspaceSearchQuery ?? this.workspaceSearchQuery,
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
        isGuest,
        joinStatus,
        joinedSessionId,
        createStatus,
        workspaces,
        workspacesStatus,
        selectedWorkspace,
        createStartTime,
        maxCapacity,
        createRules,
        workspaceSearchQuery,
      ];
}

import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../workspaces/domain/entity/workspace_entity.dart';
import '../../../workspaces/domain/use_cases/get_workspaces_use_case.dart';
import '../../../auth/domain/use_cases/get_guest_status_use_case.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/use_cases/create_buddy_session_use_case.dart';
import '../../domain/use_cases/get_buddy_sessions_use_case.dart';
import '../../domain/use_cases/join_buddy_session_use_case.dart';

part 'buddy_state.dart';

class BuddyCubit extends Cubit<BuddyState> {
  final GetBuddySessionsUseCase getBuddySessionsUseCase;
  final JoinBuddySessionUseCase joinBuddySessionUseCase;
  final CreateBuddySessionUseCase createBuddySessionUseCase;
  final GetWorkspacesUseCase getWorkspacesUseCase;
  final GetGuestStatusUseCase getGuestStatusUseCase;

  BuddyCubit({
    required this.getBuddySessionsUseCase,
    required this.joinBuddySessionUseCase,
    required this.createBuddySessionUseCase,
    required this.getWorkspacesUseCase,
    required this.getGuestStatusUseCase,
  }) : super(const BuddyState()) {
    _bootstrap();
  }

  Future<void> _bootstrap() async {
    await loadGuestStatus();
    await loadSessions();
  }

  Future<void> loadGuestStatus() async {
    final result = await getGuestStatusUseCase.call();
    result.fold(
      (_) => emit(state.copyWith(isGuest: false)),
      (isGuest) => emit(state.copyWith(isGuest: isGuest)),
    );
  }

  // ── Session list ───────────────────────────────────────────────────────────

  Future<void> loadSessions() async {
    emit(state.copyWith(status: BuddyStatus.loading));
    final result = await getBuddySessionsUseCase(
      params: BuddySessionsParams(
        university: state.universityFilter,
        subject: state.subjectFilter,
        filter: state.activeChip,
      ),
    );
    result.fold(
      (failure) => emit(state.copyWith(
        status: BuddyStatus.failure,
        errorMessage: failure.message,
      )),
      (sessions) => emit(state.copyWith(
        status: BuddyStatus.success,
        sessions: sessions,
        errorMessage: null,
      )),
    );
  }

  Future<void> refresh() => loadSessions();

  // ── Filters ────────────────────────────────────────────────────────────────

  void setUniversityFilter(String value) {
    emit(state.copyWith(universityFilter: value));
    loadSessions();
  }

  void setSubjectFilter(String value) {
    emit(state.copyWith(subjectFilter: value));
    loadSessions();
  }

  void setActiveChip(String chip) {
    emit(state.copyWith(activeChip: chip));
    loadSessions();
  }

  // ── Join session ───────────────────────────────────────────────────────────

  Future<void> joinSession(BuddySessionEntity session) async {
    if (session.isFull) return;
    emit(state.copyWith(joinStatus: BuddyActionStatus.loading));
    final result = await joinBuddySessionUseCase(session.id);
    result.fold(
      (failure) => emit(state.copyWith(joinStatus: BuddyActionStatus.failure)),
      (_) => emit(state.copyWith(
        joinStatus: BuddyActionStatus.success,
        joinedSessionId: session.id,
      )),
    );
  }

  // ── Create session ─────────────────────────────────────────────────────────

  Future<void> createSession({
    required String topic,
    required String subject,
    required String description,
    required String? gift,
  }) async {
    final workspace = state.selectedWorkspace;
    if (workspace == null) {
      emit(state.copyWith(createStatus: BuddyActionStatus.failure));
      return;
    }

    emit(state.copyWith(createStatus: BuddyActionStatus.loading));
    final result = await createBuddySessionUseCase({
      'topic': topic,
      'subject': subject,
      'description': description,
      'rules': List<String>.from(state.createRules),
      'workspaceId': workspace.id,
      'workspaceName': workspace.name,
      'workspaceAddress': workspace.address,
      'startTime': effectiveStartTime.toIso8601String(),
      'maxCapacity': state.maxCapacity,
      'gift': gift,
    });
    result.fold(
      (failure) =>
          emit(state.copyWith(createStatus: BuddyActionStatus.failure)),
      (_) {
        emit(state.copyWith(createStatus: BuddyActionStatus.success));
        resetCreateForm();
        loadSessions();
      },
    );
  }

  // ── Workspaces list (for create-session picker) ────────────────────────────

  Future<void> loadWorkspaces() async {
    if (state.workspacesStatus == BuddyStatus.loading) return;
    emit(state.copyWith(workspacesStatus: BuddyStatus.loading));
    final result = await getWorkspacesUseCase();
    result.fold(
      (failure) => emit(state.copyWith(workspacesStatus: BuddyStatus.failure)),
      (list) => emit(state.copyWith(
        workspaces: list,
        workspacesStatus: BuddyStatus.success,
      )),
    );
  }

  // ── Computed helpers ───────────────────────────────────────────────────────

  DateTime get effectiveStartTime =>
      state.createStartTime ?? DateTime.now().add(const Duration(hours: 1));

  List<WorkspaceEntity> get filteredWorkspaces {
    final q = state.workspaceSearchQuery.toLowerCase();
    if (q.isEmpty) return state.workspaces;
    return state.workspaces
        .where((ws) =>
            ws.name.toLowerCase().contains(q) ||
            ws.address.toLowerCase().contains(q))
        .toList();
  }

  String formatDateTime(DateTime dt) {
    final hour = dt.hour > 12
        ? dt.hour - 12
        : dt.hour == 0
            ? 12
            : dt.hour;
    final ampm = dt.hour >= 12 ? 'م' : 'ص';
    final min = dt.minute.toString().padLeft(2, '0');
    return '${dt.day}/${dt.month}/${dt.year}  •  $hour:$min $ampm';
  }

  // ── Create-session form actions ────────────────────────────────────────────

  void selectWorkspace(WorkspaceEntity ws) {
    emit(state.copyWith(selectedWorkspace: ws));
  }

  void setWorkspaceSearch(String query) {
    emit(state.copyWith(workspaceSearchQuery: query));
  }

  void setStartTime(DateTime value) {
    emit(state.copyWith(createStartTime: value));
  }

  void incrementCapacity() {
    if (state.maxCapacity < 20) {
      emit(state.copyWith(maxCapacity: state.maxCapacity + 1));
    }
  }

  void decrementCapacity() {
    if (state.maxCapacity > 2) {
      emit(state.copyWith(maxCapacity: state.maxCapacity - 1));
    }
  }

  void addRule(String rule) {
    final text = rule.trim();
    if (text.isEmpty) return;
    emit(state.copyWith(createRules: [...state.createRules, text]));
  }

  void removeRule(int index) {
    final updated = List<String>.from(state.createRules)..removeAt(index);
    emit(state.copyWith(createRules: updated));
  }

  void resetCreateForm() {
    emit(state.copyWith(
      createRules: const [],
      workspaceSearchQuery: '',
      maxCapacity: 4,
      createStatus: BuddyActionStatus.idle,
      clearSelectedWorkspace: true,
      clearCreateStartTime: true,
    ));
  }
}

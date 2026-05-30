import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';

import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../../../workspaces/domain/entity/workspace_entity.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/use_cases/get_buddy_sessions_use_case.dart';
import '../../domain/repository/buddy_repository.dart';

part 'buddy_state.dart';

class BuddyCubit extends Cubit<BuddyState> {
  final GetBuddySessionsUseCase getBuddySessionsUseCase;
  final BuddyRepository buddyRepository;

  BuddyCubit({
    required this.getBuddySessionsUseCase,
    required this.buddyRepository,
  }) : super(const BuddyState()) {
    loadSessions();
  }

  // ── Data ──────────────────────────────────────────────────────────────────

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

  // ── Filters ───────────────────────────────────────────────────────────────

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

  // ── Join session ──────────────────────────────────────────────────────────

  Future<void> joinSession(BuildContext context, BuddySessionEntity session) async {
    if (session.isFull) return;
    emit(state.copyWith(joinStatus: BuddyActionStatus.loading));
    final result = await buddyRepository.joinSession(session.id);
    result.fold(
      (failure) => emit(state.copyWith(joinStatus: BuddyActionStatus.failure)),
      (_) => emit(state.copyWith(
        joinStatus: BuddyActionStatus.success,
        joinedSessionId: session.id,
      )),
    );
  }

  // ── Create session ────────────────────────────────────────────────────────

  Future<void> createSession(
    BuildContext context, {
    required String topic,
    required String subject,
    required String description,
    required List<String> rules,
    required String workspaceId,
    required String workspaceName,
    required String workspaceAddress,
    required DateTime startTime,
    required int maxCapacity,
    String? gift,
  }) async {
    emit(state.copyWith(createStatus: BuddyActionStatus.loading));
    final result = await buddyRepository.createSession({
      'topic': topic,
      'subject': subject,
      'description': description,
      'rules': rules,
      'workspaceId': workspaceId,
      'workspaceName': workspaceName,
      'workspaceAddress': workspaceAddress,
      'startTime': startTime.toIso8601String(),
      'maxCapacity': maxCapacity,
      'gift': gift,
    });
    result.fold(
      (failure) => emit(state.copyWith(createStatus: BuddyActionStatus.failure)),
      (_) {
        emit(state.copyWith(createStatus: BuddyActionStatus.success));
        loadSessions();
        context.pop();
      },
    );
  }

  // ── Navigation ────────────────────────────────────────────────────────────

  void openSessionDetails(BuildContext context, BuddySessionEntity session) {
    context.pushNamed(
      DRoutesName.sessionDetailsRoute,
      arguments: {'session': session},
    );
  }

  void openCreateSession(BuildContext context) {
    context.pushNamed(DRoutesName.createSessionRoute);
  }

  /// Navigates to the workspace details screen. Builds a minimal [WorkspaceEntity]
  /// from the session's embedded workspace fields so the route receives the
  /// full object it expects.
  void openWorkspaceDetails(BuildContext context, BuddySessionEntity session) {
    final workspace = WorkspaceEntity(
      id: session.workspaceId,
      name: session.workspaceName,
      address: session.workspaceAddress,
      currentOccupancy: 0,
      capacity: 0,
      status: WorkspaceStatus.open,
      distanceKm: 0.0,
      openTime: '',
      closeTime: '',
      amenities: const [],
    );
    context.pushNamed(
      DRoutesName.workspaceDetailsRoute,
      arguments: {'workspace': workspace},
    );
  }
}

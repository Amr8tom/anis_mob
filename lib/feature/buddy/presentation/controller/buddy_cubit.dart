import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/material.dart';

import '../../../../core/constants/colors.dart';
import '../../../../core/extentions/navigation_extension.dart';
import '../../../../core/routing/route_names.dart';
import '../../../../generated/l10n.dart';
import '../../../workspaces/domain/entity/workspace_entity.dart';
import '../../../workspaces/domain/use_cases/get_workspaces_use_case.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/use_cases/get_buddy_sessions_use_case.dart';
import '../../domain/repository/buddy_repository.dart';

part 'buddy_state.dart';

class BuddyCubit extends Cubit<BuddyState> {
  final GetBuddySessionsUseCase getBuddySessionsUseCase;
  final BuddyRepository buddyRepository;
  final GetWorkspacesUseCase getWorkspacesUseCase;

  BuddyCubit({
    required this.getBuddySessionsUseCase,
    required this.buddyRepository,
    required this.getWorkspacesUseCase,
  }) : super(const BuddyState()) {
    loadSessions();
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

  // ── Create session ─────────────────────────────────────────────────────────

  Future<void> createSession(
    BuildContext context, {
    required String topic,
    required String subject,
    required String description,
    required List<String> rules,
    required WorkspaceEntity workspace,
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
      'workspaceId': workspace.id,
      'workspaceName': workspace.name,
      'workspaceAddress': workspace.address,
      'startTime': startTime.toIso8601String(),
      'maxCapacity': maxCapacity,
      'gift': gift,
    });
    result.fold(
      (failure) => emit(state.copyWith(createStatus: BuddyActionStatus.failure)),
      (_) {
        emit(state.copyWith(createStatus: BuddyActionStatus.success));
        resetCreateForm();
        loadSessions();
        context.pop();
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

  // ── Create-session form controllers ────────────────────────────────────────

  final createFormKey = GlobalKey<FormState>();
  final topicCtrl = TextEditingController();
  final subjectCtrl = TextEditingController();
  final descCtrl = TextEditingController();
  final giftCtrl = TextEditingController();
  final ruleCtrl = TextEditingController();
  final workspaceSearchCtrl = TextEditingController();

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
    final hour = dt.hour > 12 ? dt.hour - 12 : dt.hour == 0 ? 12 : dt.hour;
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

  void addRule() {
    final text = ruleCtrl.text.trim();
    if (text.isEmpty) return;
    emit(state.copyWith(createRules: [...state.createRules, text]));
    ruleCtrl.clear();
  }

  void removeRule(int index) {
    final updated = List<String>.from(state.createRules)..removeAt(index);
    emit(state.copyWith(createRules: updated));
  }

  Future<void> pickDateTime(BuildContext context) async {
    final date = await showDatePicker(
      context: context,
      initialDate: effectiveStartTime,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (date == null) return;
    if (!context.mounted) return;

    final time = await showTimePicker(
      context: context,
      initialTime: TimeOfDay.fromDateTime(effectiveStartTime),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (time == null) return;

    emit(state.copyWith(
      createStartTime: DateTime(
        date.year, date.month, date.day, time.hour, time.minute,
      ),
    ));
  }

  Future<void> submitCreateSession(BuildContext context) async {
    if (!createFormKey.currentState!.validate()) return;
    if (state.selectedWorkspace == null) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(S.current.selectWorkspaceHint),
        backgroundColor: ColorRes.anisErrorRed,
        behavior: SnackBarBehavior.floating,
      ));
      return;
    }
    await createSession(
      context,
      topic: topicCtrl.text.trim(),
      subject: subjectCtrl.text.trim(),
      description: descCtrl.text.trim(),
      rules: List.from(state.createRules),
      workspace: state.selectedWorkspace!,
      startTime: effectiveStartTime,
      maxCapacity: state.maxCapacity,
      gift: giftCtrl.text.trim().isEmpty ? null : giftCtrl.text.trim(),
    );
  }

  void resetCreateForm() {
    topicCtrl.clear();
    subjectCtrl.clear();
    descCtrl.clear();
    giftCtrl.clear();
    ruleCtrl.clear();
    workspaceSearchCtrl.clear();
    emit(state.copyWith(
      createRules: const [],
      workspaceSearchQuery: '',
      maxCapacity: 4,
      createStatus: BuddyActionStatus.idle,
      clearSelectedWorkspace: true,
      clearCreateStartTime: true,
    ));
  }

  // ── Navigation ─────────────────────────────────────────────────────────────

  void openSessionDetails(BuildContext context, BuddySessionEntity session) {
    context.pushNamed(
      DRoutesName.sessionDetailsRoute,
      arguments: {'session': session},
    );
  }

  void openCreateSession(BuildContext context) {
    context.pushNamed(DRoutesName.createSessionRoute);
  }

  /// Navigates to workspace details using the full [WorkspaceEntity] already
  /// embedded in the session — no lookup or construction needed.
  void openWorkspaceDetails(BuildContext context, BuddySessionEntity session) {
    context.pushNamed(
      DRoutesName.workspaceDetailsRoute,
      arguments: {'workspace': session.workspace},
    );
  }

  // ── Lifecycle ──────────────────────────────────────────────────────────────

  @override
  Future<void> close() {
    topicCtrl.dispose();
    subjectCtrl.dispose();
    descCtrl.dispose();
    giftCtrl.dispose();
    ruleCtrl.dispose();
    workspaceSearchCtrl.dispose();
    return super.close();
  }
}

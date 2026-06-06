part of 'workspace_cubit.dart';

enum WorkspaceStatus2 { initial, loading, success, failure }

const Object _workspaceUnset = Object();

final class WorkspaceState extends Equatable {
  final WorkspaceStatus2 status;
  final List<WorkspaceEntity> workspaces;
  final String activeFilter; // 'all' | 'openNow' | 'nearby'
  final String? errorMessage;

  const WorkspaceState({
    this.status = WorkspaceStatus2.initial,
    this.workspaces = const [],
    this.activeFilter = 'all',
    this.errorMessage,
  });

  WorkspaceState copyWith({
    WorkspaceStatus2? status,
    List<WorkspaceEntity>? workspaces,
    String? activeFilter,
    Object? errorMessage = _workspaceUnset,
  }) {
    return WorkspaceState(
      status: status ?? this.status,
      workspaces: workspaces ?? this.workspaces,
      activeFilter: activeFilter ?? this.activeFilter,
      errorMessage: identical(errorMessage, _workspaceUnset)
          ? this.errorMessage
          : errorMessage as String?,
    );
  }

  @override
  List<Object?> get props => [status, workspaces, activeFilter, errorMessage];
}

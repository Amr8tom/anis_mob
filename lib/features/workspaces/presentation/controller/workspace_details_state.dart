part of 'workspace_details_cubit.dart';

enum WorkspaceDetailsStatus { initial, loading, success, failure }

final class WorkspaceDetailsState extends Equatable {
  final WorkspaceDetailsStatus status;
  final WorkspaceEntity? workspace;
  final String? errorMessage;

  const WorkspaceDetailsState({
    this.status = WorkspaceDetailsStatus.initial,
    this.workspace,
    this.errorMessage,
  });

  WorkspaceDetailsState copyWith({
    WorkspaceDetailsStatus? status,
    WorkspaceEntity? workspace,
    String? errorMessage,
    bool clearErrorMessage = false,
  }) {
    return WorkspaceDetailsState(
      status: status ?? this.status,
      workspace: workspace ?? this.workspace,
      errorMessage:
          clearErrorMessage ? null : (errorMessage ?? this.errorMessage),
    );
  }

  @override
  List<Object?> get props => [status, workspace, errorMessage];
}

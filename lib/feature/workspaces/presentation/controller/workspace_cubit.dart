import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

import '../../domain/entity/workspace_entity.dart';
import '../../domain/use_cases/get_workspaces_use_case.dart';

part 'workspace_state.dart';

class WorkspaceCubit extends Cubit<WorkspaceState> {
  final GetWorkspacesUseCase getWorkspacesUseCase;

  WorkspaceCubit({required this.getWorkspacesUseCase})
      : super(const WorkspaceState()) {
    loadWorkspaces();
  }

  Future<void> loadWorkspaces() async {
    emit(state.copyWith(status: WorkspaceStatus2.loading));
    final result = await getWorkspacesUseCase(
      filter: state.activeFilter == 'all' ? null : state.activeFilter,
    );
    result.fold(
      (failure) => emit(state.copyWith(
        status: WorkspaceStatus2.failure,
        errorMessage: failure.message,
      )),
      (workspaces) => emit(state.copyWith(
        status: WorkspaceStatus2.success,
        workspaces: workspaces,
        errorMessage: null,
      )),
    );
  }

  void setFilter(String filter) {
    emit(state.copyWith(activeFilter: filter));
    loadWorkspaces();
  }

  Future<void> refresh() => loadWorkspaces();
}

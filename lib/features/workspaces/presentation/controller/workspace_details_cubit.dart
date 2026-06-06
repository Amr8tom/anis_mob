import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../domain/entity/workspace_entity.dart';
import '../../domain/use_cases/get_workspace_details_use_case.dart';

part 'workspace_details_state.dart';

class WorkspaceDetailsCubit extends Cubit<WorkspaceDetailsState> {
  final GetWorkspaceDetailsUseCase getWorkspaceDetailsUseCase;
  final String workspaceId;

  WorkspaceDetailsCubit({
    required this.getWorkspaceDetailsUseCase,
    required this.workspaceId,
  }) : super(const WorkspaceDetailsState()) {
    loadWorkspace();
  }

  Future<void> loadWorkspace() async {
    emit(state.copyWith(status: WorkspaceDetailsStatus.loading));

    final result = await getWorkspaceDetailsUseCase(workspaceId);
    result.fold(
      (failure) => emit(
        state.copyWith(
          status: WorkspaceDetailsStatus.failure,
          errorMessage: failure.message,
        ),
      ),
      (workspace) => emit(
        state.copyWith(
          status: WorkspaceDetailsStatus.success,
          workspace: workspace,
          clearErrorMessage: true,
        ),
      ),
    );
  }
}

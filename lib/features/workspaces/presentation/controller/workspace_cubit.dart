import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';
import '../../domain/entity/workspace_entity.dart';
import '../../domain/use_cases/get_workspaces_use_case.dart';

part 'workspace_state.dart';

class WorkspaceCubit extends Cubit<WorkspaceState> {
  final GetWorkspacesUseCase getWorkspacesUseCase;
  final LocalStorage localStorage;

  WorkspaceCubit({
    required this.getWorkspacesUseCase,
    required this.localStorage,
  }) : super(const WorkspaceState()) {
    loadWorkspaces();
  }

  Future<void> loadWorkspaces() async {
    _emitLoadingState();

    final lat = localStorage.getDouble(key: StorageKeys.latitude.name, defaultValue: -999.0);
    final lng = localStorage.getDouble(key: StorageKeys.longitude.name, defaultValue: -999.0);

    final result = await getWorkspacesUseCase(
      filter: _filterForApi(state.activeFilter),
      latitude: lat != -999.0 ? lat : null,
      longitude: lng != -999.0 ? lng : null,
    );

    result.fold(_emitFailureState, _emitSuccessState);
  }

  void setFilter(String filter) {
    if (filter == state.activeFilter) return;
    _emitFilterState(filter);
    loadWorkspaces();
  }

  Future<void> refresh() => loadWorkspaces();

  void _emitLoadingState() {
    emit(state.copyWith(status: WorkspaceStatus2.loading));
  }

  void _emitSuccessState(List<WorkspaceEntity> workspaces) {
    emit(
      state.copyWith(
        status: WorkspaceStatus2.success,
        workspaces: workspaces,
        errorMessage: null,
      ),
    );
  }

  void _emitFailureState(Failure failure) {
    emit(
      state.copyWith(
        status: WorkspaceStatus2.failure,
        errorMessage: failure.message,
      ),
    );
  }

  void _emitFilterState(String filter) {
    emit(state.copyWith(activeFilter: filter));
  }

  String? _filterForApi(String filter) {
    if (filter == 'all') return null;
    return filter;
  }
}

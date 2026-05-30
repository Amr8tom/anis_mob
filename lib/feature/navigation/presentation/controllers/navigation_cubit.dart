import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:flutter/widgets.dart';
import 'package:meta/meta.dart';

import '../../../buddy/presentation/screens/buddy_screen.dart';
import '../../../home/home_screen.dart';
import '../../../profile/presentation/screens/profile_screen.dart';
import '../../../workspaces/presentation/screens/workspaces_screen.dart';

part 'navigation_state.dart';

class NavigationCubit extends Cubit<NavigationState> {
  NavigationCubit() : super(const NavigationState());

  int indx = 0;

  void changeIndex(int index) {
    emit(state.copyWith(status: NavigationStatus.loading));
    indx = index;
    emit(state.copyWith(status: NavigationStatus.indexChanged));
  }
}

enum NavigationStatus { initialized, indexChanged, loading, success, error }

extension NavigationStatusExtension on NavigationStatus {
  bool get isInitialized => this == NavigationStatus.initialized;
  bool get isLoading => this == NavigationStatus.loading;
  bool get isSuccess => this == NavigationStatus.success;
  bool get isIndexChanged => this == NavigationStatus.indexChanged;
  bool get isError => this == NavigationStatus.error;
}

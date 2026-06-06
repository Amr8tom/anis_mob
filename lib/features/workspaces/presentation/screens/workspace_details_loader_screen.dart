import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/custom_ui.dart';
import '../../../../core/constants/colors.dart';
import '../controller/workspace_details_cubit.dart';
import 'workspace_details_screen.dart';

class WorkspaceDetailsLoaderScreen extends StatelessWidget {
  const WorkspaceDetailsLoaderScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<WorkspaceDetailsCubit, WorkspaceDetailsState>(
      builder: (context, state) {
        if (state.status == WorkspaceDetailsStatus.success &&
            state.workspace != null) {
          return WorkspaceDetailsScreen(workspace: state.workspace!);
        }

        if (state.status == WorkspaceDetailsStatus.failure) {
          return Scaffold(
            backgroundColor: ColorRes.anisMintBg,
            body: SafeArea(
              child: CustomUI.anisErrorState(
                context: context,
                message: state.errorMessage ?? '',
                onRetry: context.read<WorkspaceDetailsCubit>().loadWorkspace,
              ),
            ),
          );
        }

        return const Scaffold(
          backgroundColor: ColorRes.anisMintBg,
          body: Center(
            child: CircularProgressIndicator(color: ColorRes.anisGreen),
          ),
        );
      },
    );
  }
}

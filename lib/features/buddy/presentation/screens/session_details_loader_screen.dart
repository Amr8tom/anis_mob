import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/custom_ui.dart';
import '../../../../core/constants/colors.dart';
import '../controller/buddy_session_details_cubit.dart';
import 'session_details_screen.dart';

class SessionDetailsLoaderScreen extends StatelessWidget {
  const SessionDetailsLoaderScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BuddySessionDetailsCubit, BuddySessionDetailsState>(
      builder: (context, state) {
        if (state.status == BuddySessionDetailsStatus.success &&
            state.session != null) {
          return SessionDetailsScreen(session: state.session!);
        }

        if (state.status == BuddySessionDetailsStatus.failure) {
          return Scaffold(
            backgroundColor: ColorRes.anisMintBg,
            body: SafeArea(
              child: CustomUI.anisErrorState(
                context: context,
                message: state.errorMessage ?? '',
                onRetry: context.read<BuddySessionDetailsCubit>().loadSession,
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

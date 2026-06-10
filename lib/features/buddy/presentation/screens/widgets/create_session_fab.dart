import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../common/action_guard.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/extentions/navigation_extension.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../generated/l10n.dart';
import '../../controller/buddy_cubit.dart';

class CreateSessionFab extends StatelessWidget {
  const CreateSessionFab({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BuddyCubit, BuddyState>(
      buildWhen: (previous, current) => previous.isGuest != current.isGuest,
      builder: (context, state) {
        return FloatingActionButton.extended(
          onPressed: () => ActionGuard.run(
            context,
            isGuest: state.isGuest,
            action: () => context.pushNamed(DRoutesName.createSessionRoute),
          ),
          backgroundColor: ColorRes.anisGreen,
          foregroundColor: ColorRes.white,
          elevation: 4,
          icon: const Icon(Icons.add_rounded),
          label: Text(
            S.current.createSession,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  fontWeight: FontWeight.w700,
                  color: ColorRes.white,
                ),
          ),
        );
      },
    );
  }
}

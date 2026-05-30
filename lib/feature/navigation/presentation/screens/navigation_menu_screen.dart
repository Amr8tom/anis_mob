import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:upgrader/upgrader.dart';

import '../../../../common/widgets/navigationbar/bottom_navigation_bar.dart';
import '../../../../core/connection/check_for_updates.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/service_locator/service_locator.dart';
import '../../../buddy/presentation/controller/buddy_cubit.dart';
import '../../../home/presentation/controller/home_cubit.dart';
import '../../../profile/presentation/controller/profile_cubit.dart';
import '../../../workspaces/presentation/controller/workspace_cubit.dart';
import '../controllers/navigation_cubit.dart';

class NavigationMenuScreen extends StatelessWidget {
  NavigationMenuScreen({super.key});

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();

  @override
  Widget build(BuildContext context) {
    checkForUpdate(context: context);
    return MultiBlocProvider(
      providers: [
        BlocProvider(create: (_) => serviceLocator<NavigationCubit>()),
        BlocProvider(create: (_) => serviceLocator<HomeCubit>()),
        BlocProvider(create: (_) => serviceLocator<BuddyCubit>()),
        BlocProvider(create: (_) => serviceLocator<WorkspaceCubit>()),
        BlocProvider(create: (_) => serviceLocator<ProfileCubit>()),
      ],
      child: Builder(
        builder: (context) {
          final navCubit = context.watch<NavigationCubit>();

          return UpgradeAlert(
            child: Scaffold(
              key: scaffoldKey,
              backgroundColor: ColorRes.white,
              body: BlocConsumer<NavigationCubit, NavigationState>(
                listener: (_, __) {},
                builder: (context, state) {
                  return state.screens[navCubit.indx];
                },
              ),
              bottomNavigationBar: const CustomBottomNavigationBar(),
            ),
          );
        },
      ),
    );
  }
}

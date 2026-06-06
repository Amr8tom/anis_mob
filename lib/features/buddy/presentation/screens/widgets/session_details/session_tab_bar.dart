import 'package:flutter/material.dart';

import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class SessionTabBar extends StatelessWidget implements PreferredSizeWidget {
  final TabController controller;
  const SessionTabBar({super.key, required this.controller});

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Container(
      color: ColorRes.white,
      child: TabBar(
        controller: controller,
        labelColor: ColorRes.anisGreen,
        unselectedLabelColor: ColorRes.anisHintText,
        indicatorColor: ColorRes.anisGreen,
        indicatorWeight: 2.5,
        indicatorSize: TabBarIndicatorSize.label,
        dividerColor: ColorRes.anisLine,
        labelStyle: tt.bodyMedium?.copyWith(fontWeight: FontWeight.w700),
        unselectedLabelStyle: tt.bodyMedium,
        tabs: [
          Tab(text: S.current.sessionTabOverview),
          Tab(text: S.current.sessionTabPeople),
        ],
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../domain/entity/buddy_session_entity.dart';
import 'widgets/session_details/session_header_section.dart';
import 'widgets/session_details/session_join_bar.dart';
import 'widgets/session_details/session_overview_tab.dart';
import 'widgets/session_details/session_people_tab.dart';
import 'widgets/session_details/session_tab_bar.dart';

class SessionDetailsScreen extends StatefulWidget {
  final BuddySessionEntity session;
  const SessionDetailsScreen({super.key, required this.session});

  @override
  State<SessionDetailsScreen> createState() => _SessionDetailsScreenState();
}

class _SessionDetailsScreenState extends State<SessionDetailsScreen>
    with SingleTickerProviderStateMixin {
  late final TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final session = widget.session;
    final isFull = session.sessionStatus == BuddySessionStatus.full;

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.light,
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        body: Stack(
          children: [
            Column(
              children: [
                SessionHeaderSection(session: session),
                SessionTabBar(controller: _tabController),
                Expanded(
                  child: TabBarView(
                    controller: _tabController,
                    children: [
                      SingleChildScrollView(
                        physics: const BouncingScrollPhysics(),
                        padding: EdgeInsets.only(bottom: AppSizes.xxl),
                        child: SessionOverviewTab(session: session),
                      ),
                      SingleChildScrollView(
                        physics: const BouncingScrollPhysics(),
                        padding: EdgeInsets.only(bottom: AppSizes.xxl),
                        child: SessionPeopleTab(session: session),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: SessionJoinBar(session: session, isFull: isFull),
            ),
          ],
        ),
      ),
    );
  }
}

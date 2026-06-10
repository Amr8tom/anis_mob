import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controller/buddy_cubit.dart';
import 'widgets/create_session/create_session_basic_info_section.dart';
import 'widgets/create_session/create_session_gift_section.dart';
import 'widgets/create_session/create_session_rules_section.dart';
import 'widgets/create_session/create_session_schedule_section.dart';
import 'widgets/create_session/create_session_submit_button.dart';
import 'widgets/create_session/create_session_workspace_section.dart';

/// Screen for creating a buddy session.
class CreateSessionScreen extends StatefulWidget {
  const CreateSessionScreen({super.key});

  @override
  State<CreateSessionScreen> createState() => _CreateSessionScreenState();
}

class _CreateSessionScreenState extends State<CreateSessionScreen> {
  final _formKey = GlobalKey<FormState>();
  final _topicCtrl = TextEditingController();
  final _subjectCtrl = TextEditingController();
  final _descCtrl = TextEditingController();
  final _giftCtrl = TextEditingController();
  final _workspaceSearchCtrl = TextEditingController();
  final _ruleCtrl = TextEditingController();

  @override
  void dispose() {
    _topicCtrl.dispose();
    _subjectCtrl.dispose();
    _descCtrl.dispose();
    _giftCtrl.dispose();
    _workspaceSearchCtrl.dispose();
    _ruleCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();
    return BlocListener<BuddyCubit, BuddyState>(
      listenWhen: (previous, current) =>
          previous.createStatus != current.createStatus,
      listener: (context, state) {
        if (state.createStatus == BuddyActionStatus.success) {
          Navigator.of(context).pop();
        }
      },
      child: Scaffold(
        backgroundColor: ColorRes.anisMintBg,
        appBar: DAppBar(
          showBackArrow: true,
          title: S.current.createSessionTitle,
        ),
        body: Form(
          key: _formKey,
          child: SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            padding: EdgeInsets.fromLTRB(
              AppSizes.padding,
              AppSizes.md,
              AppSizes.padding,
              AppSizes.xl * 2,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CreateSessionBasicInfoSection(
                  topicCtrl: _topicCtrl,
                  subjectCtrl: _subjectCtrl,
                  descCtrl: _descCtrl,
                ),
                const Sizer(height: 20),
                CreateSessionScheduleSection(),
                const Sizer(height: 20),
                CreateSessionWorkspaceSection(
                  workspaceSearchCtrl: _workspaceSearchCtrl,
                ),
                const Sizer(height: 20),
                CreateSessionRulesSection(
                  ruleCtrl: _ruleCtrl,
                  onAddRule: () {
                    cubit.addRule(_ruleCtrl.text);
                    _ruleCtrl.clear();
                  },
                ),
                const Sizer(height: 20),
                CreateSessionGiftSection(
                  giftCtrl: _giftCtrl,
                ),
                const Sizer(height: 28),
                CreateSessionSubmitButton(
                  onSubmit: () {
                    if (_formKey.currentState?.validate() ?? false) {
                      cubit.createSession(
                        topic: _topicCtrl.text.trim(),
                        subject: _subjectCtrl.text.trim(),
                        description: _descCtrl.text.trim(),
                        gift: _giftCtrl.text.trim().isEmpty ? null : _giftCtrl.text.trim(),
                      );
                    }
                  },
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

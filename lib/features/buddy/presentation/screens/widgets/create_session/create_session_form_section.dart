import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';
import 'create_session_basic_info_section.dart';
import 'create_session_gift_section.dart';
import 'create_session_rules_section.dart';
import 'create_session_schedule_section.dart';
import 'create_session_submit_button.dart';
import 'create_session_workspace_section.dart';

class CreateSessionFormSection extends StatefulWidget {
  const CreateSessionFormSection({super.key});

  @override
  State<CreateSessionFormSection> createState() => _CreateSessionFormSectionState();
}

class _CreateSessionFormSectionState extends State<CreateSessionFormSection> {
  late final GlobalKey<FormState> _formKey;
  late final TextEditingController _topicCtrl;
  late final TextEditingController _subjectCtrl;
  late final TextEditingController _descCtrl;
  late final TextEditingController _giftCtrl;
  late final TextEditingController _ruleCtrl;
  late final TextEditingController _workspaceSearchCtrl;

  @override
  void initState() {
    super.initState();
    _formKey = GlobalKey<FormState>();
    _topicCtrl = TextEditingController();
    _subjectCtrl = TextEditingController();
    _descCtrl = TextEditingController();
    _giftCtrl = TextEditingController();
    _ruleCtrl = TextEditingController();
    _workspaceSearchCtrl = TextEditingController();
  }

  @override
  void dispose() {
    _topicCtrl.dispose();
    _subjectCtrl.dispose();
    _descCtrl.dispose();
    _giftCtrl.dispose();
    _ruleCtrl.dispose();
    _workspaceSearchCtrl.dispose();
    super.dispose();
  }

  void _submit() {
    final cubit = context.read<BuddyCubit>();
    if (_formKey.currentState?.validate() ?? false) {
      if (cubit.state.selectedWorkspace == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(S.current.selectWorkspaceHint),
            backgroundColor: ColorRes.anisErrorRed,
            behavior: SnackBarBehavior.floating,
          ),
        );
        return;
      }
      cubit.createSession(
        topic: _topicCtrl.text.trim(),
        subject: _subjectCtrl.text.trim(),
        description: _descCtrl.text.trim(),
        gift: _giftCtrl.text.trim().isEmpty ? null : _giftCtrl.text.trim(),
      );
    }
  }

  void _addRule() {
    final rule = _ruleCtrl.text.trim();
    if (rule.isEmpty) return;
    context.read<BuddyCubit>().addRule(rule);
    _ruleCtrl.clear();
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CreateSessionBasicInfoSection(
            topicCtrl: _topicCtrl,
            subjectCtrl: _subjectCtrl,
            descCtrl: _descCtrl,
          ),
          const Sizer(height: 20),
          const CreateSessionScheduleSection(),
          const Sizer(height: 20),
          CreateSessionWorkspaceSection(
            workspaceSearchCtrl: _workspaceSearchCtrl,
          ),
          const Sizer(height: 20),
          CreateSessionRulesSection(
            ruleCtrl: _ruleCtrl,
            onAddRule: _addRule,
          ),
          const Sizer(height: 20),
          CreateSessionGiftSection(
            giftCtrl: _giftCtrl,
          ),
          const Sizer(height: 28),
          CreateSessionSubmitButton(
            onSubmit: _submit,
          ),
        ],
      ),
    );
  }
}

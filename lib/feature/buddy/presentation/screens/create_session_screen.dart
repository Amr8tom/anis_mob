import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../common/widgets/appbar/appbar.dart';
import '../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../generated/l10n.dart';
import '../controller/buddy_cubit.dart';

// ─── Simple workspace option data ────────────────────────────────────────────

class _WorkspaceOption {
  final String id;
  final String name;
  final String address;
  const _WorkspaceOption(this.id, this.name, this.address);
}

const _workspaceOptions = [
  _WorkspaceOption('ws_001', 'مكتبة الجامعة الأمريكية', 'التجمع الخامس، القاهرة الجديدة'),
  _WorkspaceOption('ws_002', 'مساحة كوورك داون تاون', 'وسط البلد، القاهرة'),
  _WorkspaceOption('ws_003', 'مركز المعرفة', 'المهندسين، الجيزة'),
  _WorkspaceOption('ws_004', 'مكتبة بابل', 'مدينة نصر، القاهرة'),
  _WorkspaceOption('ws_005', 'هب ستوديو', 'الشيخ زايد، الجيزة'),
];

// ─── Screen ───────────────────────────────────────────────────────────────────

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
  final _ruleCtrl = TextEditingController();

  _WorkspaceOption? _selectedWorkspace;
  DateTime _startTime = DateTime.now().add(const Duration(hours: 1));
  int _maxCapacity = 4;
  final List<String> _rules = [];

  @override
  void dispose() {
    _topicCtrl.dispose();
    _subjectCtrl.dispose();
    _descCtrl.dispose();
    _giftCtrl.dispose();
    _ruleCtrl.dispose();
    super.dispose();
  }

  void _addRule() {
    final text = _ruleCtrl.text.trim();
    if (text.isEmpty) return;
    setState(() {
      _rules.add(text);
      _ruleCtrl.clear();
    });
  }

  void _removeRule(int index) => setState(() => _rules.removeAt(index));

  Future<void> _pickTime() async {
    final date = await showDatePicker(
      context: context,
      initialDate: _startTime,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (date == null || !mounted) return;

    final time = await showTimePicker(
      context: context,
      initialTime: TimeOfDay.fromDateTime(_startTime),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (time == null || !mounted) return;

    setState(() {
      _startTime = DateTime(
        date.year, date.month, date.day, time.hour, time.minute,
      );
    });
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedWorkspace == null) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(S.current.selectWorkspaceHint),
        backgroundColor: ColorRes.anisErrorRed,
        behavior: SnackBarBehavior.floating,
      ));
      return;
    }
    await context.read<BuddyCubit>().createSession(
      context,
      topic: _topicCtrl.text.trim(),
      subject: _subjectCtrl.text.trim(),
      description: _descCtrl.text.trim(),
      rules: List.from(_rules),
      workspaceId: _selectedWorkspace!.id,
      workspaceName: _selectedWorkspace!.name,
      workspaceAddress: _selectedWorkspace!.address,
      startTime: _startTime,
      maxCapacity: _maxCapacity,
      gift: _giftCtrl.text.trim().isEmpty ? null : _giftCtrl.text.trim(),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorRes.anisMintBg,
      appBar: DAppBar(
        showBackArrow: true,
        title: S.current.createSessionTitle,
      ),
      body: Form(
        key: _formKey,
        child: SingleChildScrollView(
          padding: EdgeInsets.fromLTRB(
            AppSizes.padding, AppSizes.md, AppSizes.padding, AppSizes.xl * 2,
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // ── Section: Basic info ──────────────────────
              _SectionLabel(S.current.createSessionBasicInfo),
              const Sizer(height: 10),
              _FormCard(
                children: [
                  _FieldLabel(S.current.createSessionTopic),
                  const Sizer(height: 6),
                  _StyledField(
                    controller: _topicCtrl,
                    hint: S.current.createSessionTopicHint,
                    validator: (v) =>
                        v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
                  ),
                  const Sizer(height: 14),
                  _FieldLabel(S.current.createSessionSubject),
                  const Sizer(height: 6),
                  _StyledField(
                    controller: _subjectCtrl,
                    hint: S.current.createSessionSubjectHint,
                    validator: (v) =>
                        v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
                  ),
                  const Sizer(height: 14),
                  _FieldLabel(S.current.createSessionDescription),
                  const Sizer(height: 6),
                  _StyledField(
                    controller: _descCtrl,
                    hint: S.current.createSessionDescHint,
                    maxLines: 4,
                    validator: (v) =>
                        v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
                  ),
                ],
              ),

              const Sizer(height: 20),

              // ── Section: Schedule ────────────────────────
              _SectionLabel(S.current.createSessionSchedule),
              const Sizer(height: 10),
              _FormCard(
                children: [
                  _FieldLabel(S.current.sessionTime),
                  const Sizer(height: 8),
                  GestureDetector(
                    onTap: _pickTime,
                    child: Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(AppSizes.md),
                      decoration: BoxDecoration(
                        color: ColorRes.anisChipBg,
                        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                        border: Border.all(color: ColorRes.anisLine, width: 1),
                      ),
                      child: Row(
                        children: [
                          Icon(Icons.schedule_rounded,
                              size: AppSizes.iconSm, color: ColorRes.anisGreen),
                          const Sizer(width: 10),
                          Expanded(
                            child: Text(
                              _formatDateTime(_startTime),
                              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                fontWeight: FontWeight.w600,
                                color: ColorRes.anisNavy,
                              ),
                            ),
                          ),
                          Icon(Icons.edit_calendar_rounded,
                              size: AppSizes.iconSm, color: ColorRes.anisHintText),
                        ],
                      ),
                    ),
                  ),
                  const Sizer(height: 14),

                  // Capacity stepper
                  _FieldLabel(S.current.createSessionCapacity),
                  const Sizer(height: 8),
                  Row(
                    children: [
                      _StepperButton(
                        icon: Icons.remove_rounded,
                        onTap: () {
                          if (_maxCapacity > 2) setState(() => _maxCapacity--);
                        },
                      ),
                      const Sizer(width: 16),
                      Expanded(
                        child: Container(
                          padding: EdgeInsets.symmetric(vertical: AppSizes.sm),
                          decoration: BoxDecoration(
                            color: ColorRes.anisChipBg,
                            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                          ),
                          alignment: Alignment.center,
                          child: Text(
                            '$_maxCapacity ${S.current.createSessionPersons}',
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                              fontWeight: FontWeight.w700,
                              color: ColorRes.anisNavy,
                            ),
                          ),
                        ),
                      ),
                      const Sizer(width: 16),
                      _StepperButton(
                        icon: Icons.add_rounded,
                        onTap: () {
                          if (_maxCapacity < 20) setState(() => _maxCapacity++);
                        },
                      ),
                    ],
                  ),
                ],
              ),

              const Sizer(height: 20),

              // ── Section: Workspace ───────────────────────
              _SectionLabel(S.current.sessionPlace),
              const Sizer(height: 10),
              _FormCard(
                children: [
                  _FieldLabel(S.current.selectWorkspace),
                  const Sizer(height: 8),
                  ..._workspaceOptions.map((ws) => _WorkspaceTile(
                    workspace: ws,
                    isSelected: _selectedWorkspace?.id == ws.id,
                    onTap: () => setState(() => _selectedWorkspace = ws),
                  )),
                ],
              ),

              const Sizer(height: 20),

              // ── Section: Rules ───────────────────────────
              _SectionLabel(S.current.sessionRules),
              const Sizer(height: 10),
              _FormCard(
                children: [
                  _FieldLabel(S.current.createSessionAddRule),
                  const Sizer(height: 6),
                  Row(
                    children: [
                      Expanded(
                        child: _StyledField(
                          controller: _ruleCtrl,
                          hint: S.current.createSessionRuleHint,
                          onSubmitted: (_) => _addRule(),
                        ),
                      ),
                      const Sizer(width: 8),
                      GestureDetector(
                        onTap: _addRule,
                        child: Container(
                          width: AppSizes.iconXLarge + AppSizes.sm,
                          height: AppSizes.iconXLarge + AppSizes.sm,
                          decoration: BoxDecoration(
                            color: ColorRes.anisGreen,
                            borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                          ),
                          child: Icon(Icons.add_rounded,
                              size: AppSizes.iconSm, color: ColorRes.white),
                        ),
                      ),
                    ],
                  ),
                  if (_rules.isNotEmpty) ...[
                    const Sizer(height: 10),
                    ..._rules.asMap().entries.map(
                      (e) => _RuleChip(
                        index: e.key + 1,
                        text: e.value,
                        onRemove: () => _removeRule(e.key),
                      ),
                    ),
                  ],
                ],
              ),

              const Sizer(height: 20),

              // ── Section: Gift ────────────────────────────
              _SectionLabel(S.current.sessionGift),
              const Sizer(height: 10),
              _FormCard(
                children: [
                  _FieldLabel(S.current.createSessionGiftLabel),
                  const Sizer(height: 6),
                  _StyledField(
                    controller: _giftCtrl,
                    hint: S.current.createSessionGiftHint,
                    prefixIcon: Icons.card_giftcard_rounded,
                  ),
                ],
              ),

              const Sizer(height: 28),

              // ── Submit button ────────────────────────────
              BlocBuilder<BuddyCubit, BuddyState>(
                buildWhen: (p, c) => p.createStatus != c.createStatus,
                builder: (context, state) {
                  final isLoading = state.createStatus == BuddyActionStatus.loading;
                  return SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: isLoading ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: ColorRes.anisGreen,
                        foregroundColor: ColorRes.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
                        ),
                        padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 6),
                      ),
                      child: isLoading
                          ? SizedBox(
                              width: AppSizes.iconMd,
                              height: AppSizes.iconMd,
                              child: const CircularProgressIndicator(
                                strokeWidth: 2,
                                color: ColorRes.white,
                              ),
                            )
                          : Text(
                              S.current.createSessionSubmit,
                              style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                                fontWeight: FontWeight.w700,
                                color: ColorRes.white,
                              ),
                            ),
                    ),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDateTime(DateTime dt) {
    final hour = dt.hour > 12 ? dt.hour - 12 : dt.hour == 0 ? 12 : dt.hour;
    final ampm = dt.hour >= 12 ? 'م' : 'ص';
    final min = dt.minute.toString().padLeft(2, '0');
    return '${dt.day}/${dt.month}/${dt.year}  •  $hour:$min $ampm';
  }
}

// ─── Form helper widgets ──────────────────────────────────────────────────────

class _SectionLabel extends StatelessWidget {
  final String label;
  const _SectionLabel(this.label);

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
        fontWeight: FontWeight.w800,
        color: ColorRes.anisNavy,
      ),
    );
  }
}

class _FormCard extends StatelessWidget {
  final List<Widget> children;
  const _FormCard({required this.children});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.all(AppSizes.md),
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusXLg),
        boxShadow: [
          BoxShadow(
            color: ColorRes.anisNavy.withOpacity(0.05),
            blurRadius: AppSizes.md,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: children,
      ),
    );
  }
}

class _FieldLabel extends StatelessWidget {
  final String label;
  const _FieldLabel(this.label);

  @override
  Widget build(BuildContext context) {
    return Text(
      label,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodySmall?.copyWith(
        fontWeight: FontWeight.w700,
        color: ColorRes.anisTextMuted,
      ),
    );
  }
}

class _StyledField extends StatelessWidget {
  final TextEditingController controller;
  final String hint;
  final int maxLines;
  final String? Function(String?)? validator;
  final void Function(String)? onSubmitted;
  final IconData? prefixIcon;

  const _StyledField({
    required this.controller,
    required this.hint,
    this.maxLines = 1,
    this.validator,
    this.onSubmitted,
    this.prefixIcon,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      validator: validator,
      onFieldSubmitted: onSubmitted,
      textAlign: TextAlign.start,
      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
        color: ColorRes.anisNavy,
      ),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: Theme.of(context).textTheme.bodyMedium?.copyWith(
          color: ColorRes.anisHintText,
        ),
        prefixIcon: prefixIcon != null
            ? Icon(prefixIcon, size: AppSizes.iconSm, color: ColorRes.anisGreen)
            : null,
        filled: true,
        fillColor: ColorRes.anisChipBg,
        contentPadding: EdgeInsets.symmetric(
          horizontal: AppSizes.md,
          vertical: AppSizes.sm + 4,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisLine, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisGreen, width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisErrorRed, width: 1),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          borderSide: BorderSide(color: ColorRes.anisErrorRed, width: 1.5),
        ),
      ),
    );
  }
}

class _StepperButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback onTap;
  const _StepperButton({required this.icon, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: AppSizes.iconXLarge + AppSizes.sm,
        height: AppSizes.iconXLarge + AppSizes.sm,
        decoration: BoxDecoration(
          color: ColorRes.anisGreen.withOpacity(0.1),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(color: ColorRes.anisGreen.withOpacity(0.3), width: 1),
        ),
        child: Icon(icon, size: AppSizes.iconSm, color: ColorRes.anisGreen),
      ),
    );
  }
}

class _WorkspaceTile extends StatelessWidget {
  final _WorkspaceOption workspace;
  final bool isSelected;
  final VoidCallback onTap;
  const _WorkspaceTile({
    required this.workspace, required this.isSelected, required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 180),
        margin: EdgeInsets.only(bottom: AppSizes.sm),
        padding: EdgeInsets.all(AppSizes.sm + 4),
        decoration: BoxDecoration(
          color: isSelected
              ? ColorRes.anisGreen.withOpacity(0.07)
              : ColorRes.anisChipBg,
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
            color: isSelected ? ColorRes.anisGreen : ColorRes.anisLine,
            width: isSelected ? 1.5 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(
              Icons.location_on_rounded,
              size: AppSizes.iconSm,
              color: isSelected ? ColorRes.anisGreen : ColorRes.anisHintText,
            ),
            const Sizer(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    workspace.name,
                    textAlign: TextAlign.start,
                    style: tt.bodyMedium?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: isSelected ? ColorRes.anisGreen : ColorRes.anisNavy,
                    ),
                  ),
                  Text(
                    workspace.address,
                    textAlign: TextAlign.start,
                    style: tt.bodySmall?.copyWith(color: ColorRes.anisHintText),
                  ),
                ],
              ),
            ),
            if (isSelected)
              Icon(Icons.check_circle_rounded,
                  size: AppSizes.iconSm, color: ColorRes.anisGreen),
          ],
        ),
      ),
    );
  }
}

class _RuleChip extends StatelessWidget {
  final int index;
  final String text;
  final VoidCallback onRemove;
  const _RuleChip({required this.index, required this.text, required this.onRemove});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: AppSizes.xs + 2),
      padding: EdgeInsets.symmetric(
        horizontal: AppSizes.sm + 2, vertical: AppSizes.xs + 2,
      ),
      decoration: BoxDecoration(
        color: ColorRes.anisWarningBg,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        border: Border.all(color: ColorRes.anisGold.withOpacity(0.3), width: 1),
      ),
      child: Row(
        children: [
          Container(
            width: AppSizes.iconXs + 4,
            height: AppSizes.iconXs + 4,
            decoration: BoxDecoration(
              color: ColorRes.anisGold.withOpacity(0.3),
              shape: BoxShape.circle,
            ),
            alignment: Alignment.center,
            child: Text(
              '$index',
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                fontWeight: FontWeight.w800,
                color: ColorRes.anisGold,
                fontSize: 10,
              ),
            ),
          ),
          const Sizer(width: 8),
          Expanded(
            child: Text(
              text,
              textAlign: TextAlign.start,
              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                color: ColorRes.anisTextDark,
              ),
            ),
          ),
          GestureDetector(
            onTap: onRemove,
            child: Icon(Icons.close_rounded,
                size: AppSizes.iconXs + 2, color: ColorRes.anisHintText),
          ),
        ],
      ),
    );
  }
}

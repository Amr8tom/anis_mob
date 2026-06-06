import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';

/// Section 2 — date/time picker + capacity stepper.
class CreateSessionScheduleSection extends StatelessWidget {
  const CreateSessionScheduleSection({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.createSessionSchedule),
        const Sizer(height: 10),
        BlocBuilder<BuddyCubit, BuddyState>(
          buildWhen: (p, c) =>
              p.createStartTime != c.createStartTime ||
              p.maxCapacity != c.maxCapacity,
          builder: (context, state) {
            final cubit = context.read<BuddyCubit>();
            return SessionFormCard(
              children: [
                // ── Date & time ─────────────────────────────
                SessionFieldLabel(S.current.sessionTime),
                const Sizer(height: 8),
                GestureDetector(
                  onTap: () => _pickDateTime(context, cubit),
                  child: Container(
                    width: double.infinity,
                    padding: EdgeInsets.all(AppSizes.md),
                    decoration: BoxDecoration(
                      color: ColorRes.anisChipBg,
                      borderRadius:
                          BorderRadius.circular(AppSizes.borderRadiusLg),
                      border: Border.all(color: ColorRes.anisLine, width: 1),
                    ),
                    child: Row(
                      children: [
                        Icon(Icons.schedule_rounded,
                            size: AppSizes.iconSm, color: ColorRes.anisGreen),
                        const Sizer(width: 10),
                        Expanded(
                          child: Text(
                            cubit.formatDateTime(cubit.effectiveStartTime),
                            style: Theme.of(context)
                                .textTheme
                                .bodyMedium
                                ?.copyWith(
                                  fontWeight: FontWeight.w600,
                                  color: ColorRes.anisNavy,
                                ),
                          ),
                        ),
                        Icon(Icons.edit_calendar_rounded,
                            size: AppSizes.iconSm,
                            color: ColorRes.anisHintText),
                      ],
                    ),
                  ),
                ),
                const Sizer(height: 14),

                // ── Capacity stepper ────────────────────────
                SessionFieldLabel(S.current.createSessionCapacity),
                const Sizer(height: 8),
                Row(
                  children: [
                    _StepperButton(
                      icon: Icons.remove_rounded,
                      onTap: cubit.decrementCapacity,
                    ),
                    const Sizer(width: 16),
                    Expanded(
                      child: Container(
                        padding: EdgeInsets.symmetric(vertical: AppSizes.sm),
                        decoration: BoxDecoration(
                          color: ColorRes.anisChipBg,
                          borderRadius:
                              BorderRadius.circular(AppSizes.borderRadiusLg),
                        ),
                        alignment: Alignment.center,
                        child: Text(
                          '${state.maxCapacity} ${S.current.createSessionPersons}',
                          style:
                              Theme.of(context).textTheme.bodyMedium?.copyWith(
                                    fontWeight: FontWeight.w700,
                                    color: ColorRes.anisNavy,
                                  ),
                        ),
                      ),
                    ),
                    const Sizer(width: 16),
                    _StepperButton(
                      icon: Icons.add_rounded,
                      onTap: cubit.incrementCapacity,
                    ),
                  ],
                ),
              ],
            );
          },
        ),
      ],
    );
  }

  Future<void> _pickDateTime(
    BuildContext context,
    BuddyCubit cubit,
  ) async {
    final date = await showDatePicker(
      context: context,
      initialDate: cubit.effectiveStartTime,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (date == null || !context.mounted) return;

    final time = await showTimePicker(
      context: context,
      initialTime: TimeOfDay.fromDateTime(cubit.effectiveStartTime),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.light(primary: ColorRes.anisGreen),
        ),
        child: child!,
      ),
    );
    if (time == null) return;

    cubit.setStartTime(
      DateTime(date.year, date.month, date.day, time.hour, time.minute),
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
          color: ColorRes.anisGreen.withValues(alpha: 0.1),
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          border: Border.all(
              color: ColorRes.anisGreen.withValues(alpha: 0.3), width: 1),
        ),
        child: Icon(icon, size: AppSizes.iconSm, color: ColorRes.anisGreen),
      ),
    );
  }
}

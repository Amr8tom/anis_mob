import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';

/// Section 1 — topic, subject, description fields.
class CreateSessionBasicInfoSection extends StatelessWidget {
  const CreateSessionBasicInfoSection({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.createSessionBasicInfo),
        const Sizer(height: 10),
        SessionFormCard(
          children: [
            SessionFieldLabel(S.current.createSessionTopic),
            const Sizer(height: 6),
            SessionStyledField(
              controller: cubit.topicCtrl,
              hint: S.current.createSessionTopicHint,
              validator: (v) =>
                  v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
            ),
            const Sizer(height: 14),
            SessionFieldLabel(S.current.createSessionSubject),
            const Sizer(height: 6),
            SessionStyledField(
              controller: cubit.subjectCtrl,
              hint: S.current.createSessionSubjectHint,
              validator: (v) =>
                  v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
            ),
            const Sizer(height: 14),
            SessionFieldLabel(S.current.createSessionDescription),
            const Sizer(height: 6),
            SessionStyledField(
              controller: cubit.descCtrl,
              hint: S.current.createSessionDescHint,
              maxLines: 4,
              validator: (v) =>
                  v == null || v.trim().isEmpty ? S.current.fieldRequired : null,
            ),
          ],
        ),
      ],
    );
  }
}

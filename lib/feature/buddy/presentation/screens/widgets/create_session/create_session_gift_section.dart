import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/buddy_cubit.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';

/// Section 5 — optional gift / reward field.
class CreateSessionGiftSection extends StatelessWidget {
  const CreateSessionGiftSection({super.key});

  @override
  Widget build(BuildContext context) {
    final cubit = context.read<BuddyCubit>();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SessionSectionLabel(S.current.sessionGift),
        const Sizer(height: 10),
        SessionFormCard(
          children: [
            SessionFieldLabel(S.current.createSessionGiftLabel),
            const Sizer(height: 6),
            SessionStyledField(
              controller: cubit.giftCtrl,
              hint: S.current.createSessionGiftHint,
              prefixIcon: Icons.card_giftcard_rounded,
            ),
          ],
        ),
      ],
    );
  }
}

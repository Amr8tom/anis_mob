import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../generated/l10n.dart';
import 'session_field_label.dart';
import 'session_form_card.dart';
import 'session_section_label.dart';
import 'session_styled_field.dart';

/// Section 5 — optional gift / reward field.
class CreateSessionGiftSection extends StatelessWidget {
  final TextEditingController giftCtrl;

  const CreateSessionGiftSection({
    super.key,
    required this.giftCtrl,
  });

  @override
  Widget build(BuildContext context) {
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
              controller: giftCtrl,
              hint: S.current.createSessionGiftHint,
              prefixIcon: Icons.card_giftcard_rounded,
            ),
          ],
        ),
      ],
    );
  }
}

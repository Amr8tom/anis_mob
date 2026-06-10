import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/colors.dart';

class SectionLabel extends StatelessWidget {
  final String title;
  final String subtitle;

  const SectionLabel({
    super.key,
    required this.title,
    required this.subtitle,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: tt.titleSmall?.copyWith(
            color: ColorRes.anisNavy,
            fontWeight: FontWeight.w800,
          ),
        ),
        const Sizer(height: 3),
        Text(
          subtitle,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
        ),
      ],
    );
  }
}

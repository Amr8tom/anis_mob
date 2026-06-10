import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/colors.dart';

class MetaItem extends StatelessWidget {
  final IconData icon;
  final String label;
  const MetaItem({super.key, required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Icon(icon, size: 13, color: ColorRes.anisHintText),
        Sizer(width: 3),
        Text(
          label,
          style: tt.bodySmall?.copyWith(color: ColorRes.anisTextMuted),
        ),
      ],
    );
  }
}

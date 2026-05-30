import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/Sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class WorkspaceDescriptionSection extends StatelessWidget {
  final String description;

  const WorkspaceDescriptionSection({super.key, required this.description});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: AppSizes.padding),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            S.current.description,
            textAlign: TextAlign.start,
            style: tt.titleMedium?.copyWith(
              color: ColorRes.anisNavy,
              fontWeight: FontWeight.w700,
            ),
          ),
          const Sizer(height: 8),
          Text(
            description.isEmpty ? S.current.noData : description,
            textAlign: TextAlign.start,
            style: tt.bodyMedium?.copyWith(
              color: ColorRes.anisTextMuted,
              height: 1.6,
            ),
          ),
        ],
      ),
    );
  }
}


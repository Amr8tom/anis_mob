import 'package:flutter/material.dart';

import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../generated/l10n.dart';

class ProfileCompletionFooter extends StatelessWidget {
  final bool isSaving;
  final VoidCallback onSave;
  final VoidCallback? onSkip;

  const ProfileCompletionFooter({
    super.key,
    required this.isSaving,
    required this.onSave,
    required this.onSkip,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      decoration: const BoxDecoration(
        color: ColorRes.white,
        border: Border(top: BorderSide(color: ColorRes.anisLine)),
      ),
      child: Row(
        children: [
          TextButton(
            onPressed: onSkip,
            child: Text(
              S.current.doItLater,
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    color: ColorRes.anisTextMuted,
                    fontWeight: FontWeight.w700,
                    overflow: TextOverflow.visible,
                  ),
            ),
          ),
          const Sizer(width: 8),
          Expanded(
            child: ElevatedButton.icon(
              onPressed: isSaving ? null : onSave,
              icon: isSaving
                  ? const SizedBox(
                      width: 18,
                      height: 18,
                      child: CircularProgressIndicator(
                        strokeWidth: 2,
                        color: ColorRes.white,
                      ),
                    )
                  : const Icon(Icons.check_rounded),
              label: Text(
                S.current.saveAndContinue,
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: ColorRes.white,
                      fontWeight: FontWeight.w700,
                      overflow: TextOverflow.visible,
                    ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: ColorRes.anisGreen,
                foregroundColor: ColorRes.white,
                padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 5),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

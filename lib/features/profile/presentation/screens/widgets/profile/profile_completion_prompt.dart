import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../core/extentions/navigation_extension.dart';
import '../../../../../../core/routing/route_names.dart';
import '../../../../../../generated/l10n.dart';
import '../../../controller/profile_cubit.dart';

class ProfileCompletionPrompt extends StatelessWidget {
  final int percentage;

  const ProfileCompletionPrompt({
    super.key,
    required this.percentage,
  });

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Padding(
      padding: EdgeInsets.fromLTRB(
        AppSizes.padding,
        AppSizes.sm,
        AppSizes.padding,
        AppSizes.md,
      ),
      child: Material(
        color: ColorRes.anisTagGreen,
        borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
        child: InkWell(
          onTap: () async {
            final profileCubit = context.read<ProfileCubit>();
            final updated =
                await context.pushNamed(DRoutesName.profileCompletionRoute);
            if (updated == true) await profileCubit.refresh();
          },
          borderRadius: BorderRadius.circular(AppSizes.borderRadiusLg),
          child: Padding(
            padding: EdgeInsets.all(AppSizes.md),
            child: Row(
              children: [
                Container(
                  width: 44,
                  height: 44,
                  decoration: const BoxDecoration(
                    color: ColorRes.white,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.auto_awesome_rounded,
                    color: ColorRes.anisGreen,
                  ),
                ),
                const Sizer(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        S.current.completeProfileTitle,
                        style: tt.bodyMedium?.copyWith(
                          color: ColorRes.anisNavy,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                      const Sizer(height: 5),
                      ClipRRect(
                        borderRadius:
                            BorderRadius.circular(AppSizes.borderRadiusXXLg),
                        child: LinearProgressIndicator(
                          value: percentage / 100,
                          minHeight: 4,
                          backgroundColor:
                              ColorRes.anisGreen.withValues(alpha: 0.15),
                          valueColor: const AlwaysStoppedAnimation(
                            ColorRes.anisGreen,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const Sizer(width: 10),
                const Icon(
                  Icons.chevron_right_rounded,
                  color: ColorRes.anisGreen,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

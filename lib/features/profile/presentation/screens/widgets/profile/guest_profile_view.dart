import 'package:flutter/material.dart';
import '../../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../../core/constants/app_sizes.dart';
import '../../../../../../core/constants/colors.dart';
import '../../../../../../core/extentions/navigation_extension.dart';
import '../../../../../../core/routing/route_names.dart';
import '../../../../../../generated/l10n.dart';

class GuestProfileView extends StatelessWidget {
  const GuestProfileView({super.key});

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: ColorRes.white,
      body: SafeArea(
        child: Center(
          child: Padding(
            padding: EdgeInsets.all(AppSizes.xl),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 72,
                  height: 72,
                  decoration: const BoxDecoration(
                    color: ColorRes.anisTagGreen,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.person_outline_rounded,
                    color: ColorRes.anisGreen,
                    size: 34,
                  ),
                ),
                const Sizer(height: 18),
                Text(
                  S.current.signInToSeeProfile,
                  textAlign: TextAlign.center,
                  style: tt.titleLarge?.copyWith(
                    color: ColorRes.anisNavy,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const Sizer(height: 7),
                Text(
                  S.current.signInToSeeProfileSubtitle,
                  textAlign: TextAlign.center,
                  style: tt.bodyMedium?.copyWith(
                    color: ColorRes.anisTextMuted,
                    height: 1.5,
                  ),
                ),
                const Sizer(height: 20),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () => context.pushReplacementNamed(
                      DRoutesName.loginRoute,
                    ),
                    icon: const Icon(Icons.login_rounded),
                    label: Text(S.current.signIn),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: ColorRes.anisGreen,
                      foregroundColor: ColorRes.white,
                      padding: EdgeInsets.symmetric(vertical: AppSizes.sm + 5),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

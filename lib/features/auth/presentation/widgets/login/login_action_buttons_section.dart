import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../../common/widgets/animations/animated_entrance.dart';
import '../../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../../core/constants/colors.dart';
import '../../../../../core/extentions/navigation_extension.dart';
import '../../../../../core/routing/route_names.dart';
import '../../../../../generated/l10n.dart';

import '../../controller/login/login_cubit.dart';
import 'login_button_widget.dart';
import 'login_guest_button_widget.dart';
import 'login_or_divider_widget.dart';
import 'login_signup_button_widget.dart';

class LoginActionButtonsSection extends StatelessWidget {
  final VoidCallback onSubmit;

  const LoginActionButtonsSection({
    super.key,
    required this.onSubmit,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        /// ── Login button ──────────────────────────────────
        AnimatedEntrance(
          delay: const Duration(milliseconds: 610),
          child: BlocBuilder<LoginCubit, LoginState>(
            buildWhen: (prev, curr) => prev.status != curr.status,
            builder: (_, state) => LoginButton(
              isLoading: state.status.isLoggingIn,
              onTap: state.status.isLoggingIn ? null : onSubmit,
            ),
          ),
        ),

        const Sizer(height: 12),

        const AnimatedEntrance(
          delay: Duration(milliseconds: 690),
          child: LoginSignupButton(),
        ),

        const Sizer(height: 20),

        // ── OR divider ────────────────────────────────────
        const AnimatedEntrance(
          delay: Duration(milliseconds: 760),
          child: LoginOrDivider(),
        ),

        const Sizer(height: 20),

        // ── Continue as Guest ─────────────────────────────
        const AnimatedEntrance(
          delay: Duration(milliseconds: 820),
          child: LoginGuestButton(),
        ),

        const Sizer(height: 24),

        // ── Privacy link ──────────────────────────────────
        AnimatedEntrance(
          delay: const Duration(milliseconds: 890),
          child: Center(
            child: Wrap(
              alignment: WrapAlignment.center,
              crossAxisAlignment: WrapCrossAlignment.center,
              children: [
                Text(
                  '${S.current.byLoggingInYouAgree} ',
                  style: Theme.of(context).textTheme.labelSmall?.copyWith(
                        color: ColorRes.anisTextSecondary,
                      ),
                ),
                GestureDetector(
                  onTap: () => context.pushNamed(
                    DRoutesName.termsAndConditionRoute,
                  ),
                  child: Text(
                    S.current.privacyPolicy,
                    style: Theme.of(context).textTheme.labelSmall?.copyWith(
                          color: ColorRes.anisGreen,
                          fontWeight: FontWeight.w700,
                          decoration: TextDecoration.underline,
                          decorationColor: ColorRes.anisGreen,
                        ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}

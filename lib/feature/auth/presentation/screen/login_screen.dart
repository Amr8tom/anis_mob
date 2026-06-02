import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/core/service_locator/service_locator.dart';

import '../controller/login/login_cubit.dart';
import '../widgets/login/login_form.dart';
import '../widgets/login/login_logo_hero_widget.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => serviceLocator<LoginCubit>(),
      child: AnnotatedRegion<SystemUiOverlayStyle>(
        value: SystemUiOverlayStyle.light,
        child: Scaffold(
          backgroundColor: ColorRes.anisAuthBgBottom,
          body: Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  ColorRes.anisAuthBgTop,
                  ColorRes.anisAuthBgMid,
                  ColorRes.anisAuthBgBottom,
                ],
              ),
            ),
            child: const SafeArea(
              child: Column(
                children: [
                  Expanded(flex: 5, child: LoginLogoHero()),
                  Expanded(flex: 6, child: LoginForm()),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

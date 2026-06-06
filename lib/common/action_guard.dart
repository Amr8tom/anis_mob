import 'package:flutter/material.dart';

import '../core/routing/route_names.dart';

/// Presentation-layer utility that intercepts actions for guest users.
///
/// The caller is responsible for supplying the [isGuest] flag from
/// cubit state — this keeps the guard free of any cache / business logic.
///
/// Usage — imperative (in a button onTap):
/// ```dart
/// onTap: () => ActionGuard.run(
///   context,
///   isGuest: state.isGuest,
///   action: () => cubit.joinSession(id),
/// ),
/// ```
///
/// Usage — declarative (wrapping a widget subtree):
/// ```dart
/// ActionGuard.wrap(
///   context: context,
///   isGuest: state.isGuest,
///   action: () => cubit.createSession(),
///   child: CreateSessionFab(),
/// )
/// ```
abstract final class ActionGuard {
  /// Runs [action] if [isGuest] is false.
  /// Opens the login screen and skips [action] when [isGuest] is true.
  static void run(
    BuildContext context, {
    required bool isGuest,
    required VoidCallback action,
  }) {
    if (isGuest) {
      Navigator.of(context).pushReplacementNamed(DRoutesName.loginRoute);
    } else {
      action();
    }
  }

  /// Returns a widget that intercepts taps and guards [action].
  /// [child] is rendered as-is for authenticated users.
  static Widget wrap({
    required BuildContext context,
    required bool isGuest,
    required VoidCallback action,
    required Widget child,
  }) {
    if (!isGuest) return child;

    return GestureDetector(
      behavior: HitTestBehavior.translucent,
      onTap: () => Navigator.of(context).pushReplacementNamed(
        DRoutesName.loginRoute,
      ),
      child: AbsorbPointer(child: child),
    );
  }
}

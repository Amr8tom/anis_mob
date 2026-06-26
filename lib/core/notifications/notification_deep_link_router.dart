import 'package:flutter/widgets.dart';

import '../routing/app_navigator.dart';
import '../routing/route_names.dart';

class NotificationDeepLinkRouter {
  const NotificationDeepLinkRouter();

  void open(Map<String, dynamic> data) {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final navigator = AppNavigator.key.currentState;
      if (navigator == null) return;

      final routeType = _value(data, 'deep_link_type') ?? _value(data, 'type');
      final workspaceId = _value(data, 'workspace_id');
      final sessionId =
          _value(data, 'buddy_session_id') ?? _value(data, 'session_id');

      switch (routeType) {
        case 'workspace_details':
        case 'room_reservation':
          if (workspaceId != null) {
            navigator.pushNamed(
              DRoutesName.workspaceDetailsRoute,
              arguments: {'workspaceId': workspaceId},
            );
          } else {
            navigator.pushNamed(DRoutesName.navigationMenuRoute);
          }
          return;

        case 'workspace_subscription':
          navigator.pushNamed(
            workspaceId == null
                ? DRoutesName.plansRoute
                : DRoutesName.workspaceDetailsRoute,
            arguments:
                workspaceId == null ? null : {'workspaceId': workspaceId},
          );
          return;

        case 'buddy_session':
          if (sessionId != null) {
            navigator.pushNamed(
              DRoutesName.sessionDetailsRoute,
              arguments: {'sessionId': sessionId},
            );
          } else {
            navigator.pushNamed(DRoutesName.navigationMenuRoute);
          }
          return;

        case 'public_session':
        case 'private_session':
        case 'session_reminder':
          if (workspaceId != null) {
            navigator.pushNamed(
              DRoutesName.workspaceDetailsRoute,
              arguments: {'workspaceId': workspaceId},
            );
          } else {
            navigator.pushNamed(DRoutesName.navigationMenuRoute);
          }
          return;

        default:
          navigator.pushNamed(DRoutesName.navigationMenuRoute);
      }
    });
  }

  String? _value(Map<String, dynamic> data, String key) {
    final value = data[key];
    if (value == null) return null;
    final text = value.toString();
    return text.isEmpty ? null : text;
  }
}

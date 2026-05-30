import 'package:flutter/material.dart';
import 'package:page_transition/page_transition.dart';
import 'package:anis/core/routing/route_names.dart';

import '../../feature/navigation/presentation/screens/navigation_menu_screen.dart';
import '../../feature/onboarding/presentation/screens/onboarding_screen.dart';
import '../../feature/terms_conditions/presentation/terms_conditions_screen.dart';

class RouteGenerator {
  /// generate Route

  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      /// navigation
      case DRoutesName.navigationMenuRoute:
        return PageTransition(
          child: NavigationMenuScreen(),
          type: PageTransitionType.rightToLeft,
          settings: settings,
        );

      /// onboarding Screen
      case DRoutesName.onBoardingRoute:
        return PageTransition(
          child: const OnboardingScreen(),
          type: PageTransitionType.rightToLeft,
          settings: settings,
        );

      // /// juz screen
      // case DRoutesName.juzRoute:
      //   return PageTransition(
      //     child: const JuzScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //   /// Page Screen
      // case DRoutesName.pageRoute:
      //   final Map<String, dynamic> args =
      //   settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: PageScreen(
      //       juz: args['juz'],
      //     ),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      // /// qibla  Screen
      // case DRoutesName.qiblaRoute:
      //   return PageTransition(
      //     child: const QiblaScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// Residence Location Screen
      // case DRoutesName.residenceLocationRoute:
      //   return PageTransition(
      //     child: const ResidencesScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// specific groups Screen
      // case DRoutesName.groupDetailsRoute:
      //   final Map<String, dynamic> args =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: GroupDetailsScreen(
      //       title: args["title"],
      //       supervisorName: args["supervisorName"],
      //       activities: args["activities"] ?? [],
      //     ),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// specific groups Screen
      // case DRoutesName.activityPhasesRoute:
      //   final Map<String, dynamic> args =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: ActivityScreen(
      //       title: args["title"],
      //       activityId: args["activityId"],
      //     ),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// login Screen
      // case DRoutesName.loginRoute:
      //   return PageTransition(
      //     child: const LoginScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// verify account Screen
      // case DRoutesName.verifyAccountRoute:
      //   return PageTransition(
      //     child: const VerifiedAccountScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// add new email Screen
      // case DRoutesName.addNewEmailRoute:
      //   return PageTransition(
      //     child: const AddNewEmailScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //

      //
      // /// request Screen
      // case DRoutesName.requestRoutes:
      //   final Map<String, dynamic> args =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: RequestsScreen(pilgrimId: args["requestId"]),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //

      // /// feedBack Screen
      // case DRoutesName.feedbackRoute:
      //   final Map<String, dynamic> args =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: FeedbackScreen(previousActivities: args["previousActivity"]),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// FAQ Screen
      // case DRoutesName.FAQRoute:
      //   return PageTransition(
      //     child: const FaqScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// request details  Screen
      // case DRoutesName.requestDetailsRoutes:
      //   final Map<String, dynamic> args =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: RequestDetailsScreen(
      //       title: args["title"],
      //       date: args["date"],
      //       status: args["status"],
      //       category: args["category"],
      //       description: args["description"] ?? "",
      //       supervisor_Reply: args["supervisor_Reply"] ?? "",
      //     ),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// new request  Screen
      // case DRoutesName.addNewRequestRoutes:
      //   return PageTransition(
      //     child: const SupportScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      // /// delete account Screen
      // case DRoutesName.deleteAccountRoute:
      //   return PageTransition(
      //     child: const DeleteMyAccountScreen(),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );

      // // class MapLocationScreen extends StatelessWidget {
      // // final double latitude;
      // // final double longitude;
      // // final String name, type, description;
      // //
      // // const MapLocationScreen({
      // // super.key,
      // // required this.name,
      // // required this.type,
      // // required this.description,
      // // required this.latitude,
      // // required this.longitude,
      // // });
      //
      // /// map
      // case DRoutesName.mapRoute:
      //   final Map<String, dynamic> arg =
      //       settings.arguments as Map<String, dynamic>;
      //   return PageTransition(
      //     child: MapLocationScreen(
      //       name: arg['name'] ?? '',
      //       type: arg['type'] ?? '',
      //       description: arg['des'] ?? '',
      //       latitude: arg['lat'] ?? 0.0,
      //       longitude: arg['lng'] ?? 0.0,
      //       screenTitle: arg['screenTitle'],
      //     ),
      //     type: PageTransitionType.rightToLeft,
      //     settings: settings,
      //   );
      //
      /// terms and conditions Route
      case DRoutesName.termsAndConditionRoute:
        return PageTransition(
          child: TermsConditionsScreen(),
          type: PageTransitionType.rightToLeft,
          settings: settings,
        );

      /// when no routes
      default:
        return unDefinedRoute();
    }
  }

  /// Un Defined Route
  static Route<dynamic> unDefinedRoute() {
    return MaterialPageRoute(
      builder: (_) => Scaffold(
        appBar: AppBar(title: Text('unImplemented screen')),
        body: Center(child: const Text('404 not found ')),
      ),
    );
  }
}

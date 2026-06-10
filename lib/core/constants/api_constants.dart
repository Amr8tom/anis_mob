class URL {
  /// Base URL of the Laravel backend.
  ///
  /// Defaults to the local dev server. Override per build with:
  ///   flutter run --dart-define=ANIS_API_BASE=http://10.0.2.2:8000/api/v1
  ///
  /// Notes on hosts:
  ///   • iOS simulator / desktop / web  → http://127.0.0.1:8000/api/v1
  ///   • Android emulator               → http://10.0.2.2:8000/api/v1
  ///   • Physical device                → http://[your-machine-LAN-IP]:8000/api/v1
  static const String baseUrl = String.fromEnvironment(
    'ANIS_API_BASE',
    defaultValue: 'https://hejaz2.com/api/v1',
  );

  // ── Auth ────────────────────────────────────────────────────────────────
  static const String login = '$baseUrl/auth/login';
  static const String createInfo = '$baseUrl/auth/register';
  static const String me = '$baseUrl/auth/me';
  static const String logout = '$baseUrl/auth/logout';

  // ── Home ────────────────────────────────────────────────────────────────
  static const String homeProfile = '$baseUrl/home/profile';
  static const String homeTodaySessions = '$baseUrl/home/today-sessions';

  // ── Workspaces (public) ───────────────────────────────────────────────────
  static const String workspaces = '$baseUrl/workspaces';

  static String workspaceById(String id) => '$baseUrl/workspaces/$id';

  // ── QR workspace attendance ───────────────────────────────────────────────
  static const String checkIn = '$baseUrl/workspace-visits/check-in';
  static const String activeVisit = '$baseUrl/workspace-visits/active';

  static String checkOut(String visitId) =>
      '$baseUrl/workspace-visits/$visitId/check-out';

  // ── Buddy sessions ────────────────────────────────────────────────────────
  static const String buddySessions = '$baseUrl/buddy-sessions';

  static String buddySessionById(String id) => '$baseUrl/buddy-sessions/$id';

  static String joinBuddySession(String id) =>
      '$baseUrl/buddy-sessions/$id/join';

  // ── Profile ───────────────────────────────────────────────────────────────
  static const String profile = '$baseUrl/profile';

  // ── Plans & subscriptions ─────────────────────────────────────────────────
  static const String plans = '$baseUrl/plans';
  static const String currentSubscription = '$baseUrl/subscriptions/current';

  // ── Misc ──────────────────────────────────────────────────────────────────
  static const String privacyPolicy =
      "https://sites.google.com/view/anis-privacy-policy/home";
  static const String taskUniqueName = "s-daily-update";
  static const String taskInitUniqueName = "s-one-time-scheduler";
  static const String taskName = "sBackgroundTask";
  static const String taskInistialName = "sBackgroundTaskforInitialTime";
}

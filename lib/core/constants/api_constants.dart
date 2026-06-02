class URL {
  static const String baseUrl = 'http://47.77.206.229:83/api/v1';
  static const String login = '$baseUrl/Auth/login';
  static const String user = '$baseUrl/User/';
  static const String changePassword = '$baseUrl/User/reset-password';
  static const String updateProfile = '$baseUrl/User/edit-profile';
  static const String getAllRequestsStatusCount =
      '$baseUrl/Request/status-counts/all-services';
  static const String createInfo = '$baseUrl/Auth/register';
  static const String forgetPassword = '$baseUrl/Auth/ForgetPassword';
  static const String setPassword = '$baseUrl/Auth/SetPassword';
  static const String resendOtp = '$baseUrl/Auth/ResendOtp';
  static const String sendOtp = '$baseUrl/Auth/PilgrimLoginOtp';
  static const String getCountUnreadedNotificaion = '$baseUrl/getCountUnreadedNotificaion';
  static const String privacyPolicy =
      "https://sites.google.com/view/anis-privacy-policy/home";
  static const String taskUniqueName = "s-daily-update";
  static const String taskInitUniqueName = "s-one-time-scheduler";
  static const String taskName = "sBackgroundTask";
  static const String taskInistialName = "sBackgroundTaskforInitialTime";
}

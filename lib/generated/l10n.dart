// GENERATED CODE - DO NOT MODIFY BY HAND
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'intl/messages_all.dart';

// **************************************************************************
// Generator: Flutter Intl IDE plugin
// Made by Localizely
// **************************************************************************

// ignore_for_file: non_constant_identifier_names, lines_longer_than_80_chars
// ignore_for_file: join_return_with_assignment, prefer_final_in_for_each
// ignore_for_file: avoid_redundant_argument_values, avoid_escaping_inner_quotes

class S {
  S();

  static S? _current;

  static S get current {
    assert(
      _current != null,
      'No instance of S was loaded. Try to initialize the S delegate before accessing S.current.',
    );
    return _current!;
  }

  static const AppLocalizationDelegate delegate = AppLocalizationDelegate();

  static Future<S> load(Locale locale) {
    final name = (locale.countryCode?.isEmpty ?? false)
        ? locale.languageCode
        : locale.toString();
    final localeName = Intl.canonicalizedLocale(name);
    return initializeMessages(localeName).then((_) {
      Intl.defaultLocale = localeName;
      final instance = S();
      S._current = instance;

      return instance;
    });
  }

  static S of(BuildContext context) {
    final instance = S.maybeOf(context);
    assert(
      instance != null,
      'No instance of S present in the widget tree. Did you add S.delegate in localizationsDelegates?',
    );
    return instance!;
  }

  static S? maybeOf(BuildContext context) {
    return Localizations.of<S>(context, S);
  }

  /// `teaa`
  String get appName {
    return Intl.message('teaa', name: 'appName', desc: '', args: []);
  }

  /// `SKIP INTRO`
  String get skip {
    return Intl.message('SKIP INTRO', name: 'skip', desc: '', args: []);
  }

  /// `Order Type`
  String get orderType {
    return Intl.message('Order Type', name: 'orderType', desc: '', args: []);
  }

  /// `Welcome Back,`
  String get welcome {
    return Intl.message('Welcome Back,', name: 'welcome', desc: '', args: []);
  }

  /// `Juz Index`
  String get juzIndex {
    return Intl.message('Juz Index', name: 'juzIndex', desc: '', args: []);
  }

  /// `Request Data`
  String get requestData {
    return Intl.message(
      'Request Data',
      name: 'requestData',
      desc: '',
      args: [],
    );
  }

  /// `Request Number`
  String get requestNumber {
    return Intl.message(
      'Request Number',
      name: 'requestNumber',
      desc: '',
      args: [],
    );
  }

  /// `User name or password is incorrect`
  String get authenticationError {
    return Intl.message(
      'User name or password is incorrect',
      name: 'authenticationError',
      desc: '',
      args: [],
    );
  }

  /// `Request Status`
  String get requestStatus {
    return Intl.message(
      'Request Status',
      name: 'requestStatus',
      desc: '',
      args: [],
    );
  }

  /// `Saudi`
  String get saudi {
    return Intl.message('Saudi', name: 'saudi', desc: '', args: []);
  }

  /// `Non-Saudi`
  String get nonSaudi {
    return Intl.message('Non-Saudi', name: 'nonSaudi', desc: '', args: []);
  }

  /// `Orders`
  String get orders {
    return Intl.message('Orders', name: 'orders', desc: '', args: []);
  }

  /// `Makka`
  String get makka {
    return Intl.message('Makka', name: 'makka', desc: '', args: []);
  }

  /// `Jeddah`
  String get jeddah {
    return Intl.message('Jeddah', name: 'jeddah', desc: '', args: []);
  }

  /// `This is not implemented yet`
  String get notImplementedYet {
    return Intl.message(
      'This is not implemented yet',
      name: 'notImplementedYet',
      desc: '',
      args: [],
    );
  }

  /// `Select Date`
  String get selectDate {
    return Intl.message('Select Date', name: 'selectDate', desc: '', args: []);
  }

  /// `Reject Request`
  String get rejectRequest {
    return Intl.message(
      'Reject Request',
      name: 'rejectRequest',
      desc: '',
      args: [],
    );
  }

  /// `Permission Type`
  String get permissionType {
    return Intl.message(
      'Permission Type',
      name: 'permissionType',
      desc: '',
      args: [],
    );
  }

  /// `Attachments`
  String get attachments {
    return Intl.message('Attachments', name: 'attachments', desc: '', args: []);
  }

  /// `Medical Report from Doctor`
  String get medicalReportFromDoctor {
    return Intl.message(
      'Medical Report from Doctor',
      name: 'medicalReportFromDoctor',
      desc: '',
      args: [],
    );
  }

  /// `Request Stage`
  String get requestStage {
    return Intl.message(
      'Request Stage',
      name: 'requestStage',
      desc: '',
      args: [],
    );
  }

  /// `New`
  String get NNew {
    return Intl.message('New', name: 'NNew', desc: '', args: []);
  }

  /// ` write the reason of accepting or rejecting the request`
  String get writeAcceptOrRejectReason {
    return Intl.message(
      ' write the reason of accepting or rejecting the request',
      name: 'writeAcceptOrRejectReason',
      desc: '',
      args: [],
    );
  }

  /// `Are you sure you want to logout?`
  String get logoutQuestion {
    return Intl.message(
      'Are you sure you want to logout?',
      name: 'logoutQuestion',
      desc: '',
      args: [],
    );
  }

  /// `Request Type`
  String get requestType {
    return Intl.message(
      'Request Type',
      name: 'requestType',
      desc: '',
      args: [],
    );
  }

  /// `Manager Approval`
  String get managerApproval {
    return Intl.message(
      'Manager Approval',
      name: 'managerApproval',
      desc: '',
      args: [],
    );
  }

  /// `Manager`
  String get manager {
    return Intl.message('Manager', name: 'manager', desc: '', args: []);
  }

  /// `Submitted Requests`
  String get submittedRequests {
    return Intl.message(
      'Submitted Requests',
      name: 'submittedRequests',
      desc: '',
      args: [],
    );
  }

  /// `HR Manager Approval`
  String get hrManagerApproval {
    return Intl.message(
      'HR Manager Approval',
      name: 'hrManagerApproval',
      desc: '',
      args: [],
    );
  }

  /// `Quran Kareem`
  String get quranKarem {
    return Intl.message('Quran Kareem', name: 'quranKarem', desc: '', args: []);
  }

  /// `Lighten your heart with the remembrance of Allah`
  String get lightYourHeart {
    return Intl.message(
      'Lighten your heart with the remembrance of Allah',
      name: 'lightYourHeart',
      desc: '',
      args: [],
    );
  }

  /// `Surah`
  String get surah {
    return Intl.message('Surah', name: 'surah', desc: '', args: []);
  }

  /// `Accept Request`
  String get acceptRequest {
    return Intl.message(
      'Accept Request',
      name: 'acceptRequest',
      desc: '',
      args: [],
    );
  }

  /// `Pending Request`
  String get pendingRequest {
    return Intl.message(
      'Pending Request',
      name: 'pendingRequest',
      desc: '',
      args: [],
    );
  }

  /// `Approved Request`
  String get approvedRequest {
    return Intl.message(
      'Approved Request',
      name: 'approvedRequest',
      desc: '',
      args: [],
    );
  }

  /// `Remaining Leaves`
  String get remainingLeaves {
    return Intl.message(
      'Remaining Leaves',
      name: 'remainingLeaves',
      desc: '',
      args: [],
    );
  }

  /// `Canceled Request`
  String get canceledRequest {
    return Intl.message(
      'Canceled Request',
      name: 'canceledRequest',
      desc: '',
      args: [],
    );
  }

  /// `Rejected Request`
  String get rejectedRequest {
    return Intl.message(
      'Rejected Request',
      name: 'rejectedRequest',
      desc: '',
      args: [],
    );
  }

  /// `Select File to Upload`
  String get uploadFileSelect {
    return Intl.message(
      'Select File to Upload',
      name: 'uploadFileSelect',
      desc: '',
      args: [],
    );
  }

  /// `Training Course Data`
  String get training_course_data {
    return Intl.message(
      'Training Course Data',
      name: 'training_course_data',
      desc: '',
      args: [],
    );
  }

  /// `Course Name *`
  String get course_name {
    return Intl.message(
      'Course Name *',
      name: 'course_name',
      desc: '',
      args: [],
    );
  }

  /// `Accounting Course`
  String get accounting_course {
    return Intl.message(
      'Accounting Course',
      name: 'accounting_course',
      desc: '',
      args: [],
    );
  }

  /// `Course Start Date`
  String get course_start_date {
    return Intl.message(
      'Course Start Date',
      name: 'course_start_date',
      desc: '',
      args: [],
    );
  }

  /// `Course End Date`
  String get course_end_date {
    return Intl.message(
      'Course End Date',
      name: 'course_end_date',
      desc: '',
      args: [],
    );
  }

  /// `Nomination Start Date`
  String get nomination_start_date {
    return Intl.message(
      'Nomination Start Date',
      name: 'nomination_start_date',
      desc: '',
      args: [],
    );
  }

  /// `Nomination End Date`
  String get nomination_end_date {
    return Intl.message(
      'Nomination End Date',
      name: 'nomination_end_date',
      desc: '',
      args: [],
    );
  }

  /// `Course Duration (months)`
  String get course_duration_months {
    return Intl.message(
      'Course Duration (months)',
      name: 'course_duration_months',
      desc: '',
      args: [],
    );
  }

  /// `Nomination Period (days)`
  String get nomination_period_days {
    return Intl.message(
      'Nomination Period (days)',
      name: 'nomination_period_days',
      desc: '',
      args: [],
    );
  }

  /// `Waiting`
  String get waiting {
    return Intl.message('Waiting', name: 'waiting', desc: '', args: []);
  }

  /// `Feature Coming Soon`
  String get featureComingSoon {
    return Intl.message(
      'Feature Coming Soon',
      name: 'featureComingSoon',
      desc: '',
      args: [],
    );
  }

  /// `Qibla Direction`
  String get qiblaDirectionTitle {
    return Intl.message(
      'Qibla Direction',
      name: 'qiblaDirectionTitle',
      desc: '',
      args: [],
    );
  }

  /// `I am here to help you with what benefits your religion and your journey, just tell me what you want`
  String get imHereToHelp {
    return Intl.message(
      'I am here to help you with what benefits your religion and your journey, just tell me what you want',
      name: 'imHereToHelp',
      desc: '',
      args: [],
    );
  }

  /// `Face towards the Holy Kaaba in Makkah`
  String get faceTowrds {
    return Intl.message(
      'Face towards the Holy Kaaba in Makkah',
      name: 'faceTowrds',
      desc: '',
      args: [],
    );
  }

  /// `Please try again or sign up`
  String get tryAgain {
    return Intl.message(
      'Please try again or sign up',
      name: 'tryAgain',
      desc: '',
      args: [],
    );
  }

  /// `The Kaaba`
  String get kabaa {
    return Intl.message('The Kaaba', name: 'kabaa', desc: '', args: []);
  }

  /// `Search Juz Index ...`
  String get searchJuzIndex {
    return Intl.message(
      'Search Juz Index ...',
      name: 'searchJuzIndex',
      desc: '',
      args: [],
    );
  }

  /// `Hold your device flat`
  String get holdDeviceFlat {
    return Intl.message(
      'Hold your device flat',
      name: 'holdDeviceFlat',
      desc: '',
      args: [],
    );
  }

  /// `Finding your direction to the Holy Kaaba...`
  String get findingYourDirection {
    return Intl.message(
      'Finding your direction to the Holy Kaaba...',
      name: 'findingYourDirection',
      desc: '',
      args: [],
    );
  }

  /// `Device Angle`
  String get deviceAngle {
    return Intl.message(
      'Device Angle',
      name: 'deviceAngle',
      desc: '',
      args: [],
    );
  }

  /// `Qibla Angle`
  String get qiblaAngle {
    return Intl.message('Qibla Angle', name: 'qiblaAngle', desc: '', args: []);
  }

  /// `en_US`
  String get localIID {
    return Intl.message('en_US', name: 'localIID', desc: '', args: []);
  }

  /// `Current Location`
  String get currentLocation {
    return Intl.message(
      'Current Location',
      name: 'currentLocation',
      desc: '',
      args: [],
    );
  }

  /// `Location Error`
  String get locationError {
    return Intl.message(
      'Location Error',
      name: 'locationError',
      desc: '',
      args: [],
    );
  }

  /// `Hijri Date`
  String get hijriDate {
    return Intl.message('Hijri Date', name: 'hijriDate', desc: '', args: []);
  }

  /// `Juz`
  String get juz {
    return Intl.message('Juz', name: 'juz', desc: '', args: []);
  }

  /// `Application`
  String get Application {
    return Intl.message('Application', name: 'Application', desc: '', args: []);
  }

  /// `Sub Activity Request`
  String get subActivityRequest {
    return Intl.message(
      'Sub Activity Request',
      name: 'subActivityRequest',
      desc: '',
      args: [],
    );
  }

  /// `Out of Group`
  String get OutOfGroup {
    return Intl.message('Out of Group', name: 'OutOfGroup', desc: '', args: []);
  }

  /// `By clicking on the button, you agree to our `
  String get byClickYourAgreeTerms {
    return Intl.message(
      'By clicking on the button, you agree to our ',
      name: 'byClickYourAgreeTerms',
      desc: '',
      args: [],
    );
  }

  /// `Teaa Client`
  String get appTitle {
    return Intl.message('Teaa Client', name: 'appTitle', desc: '', args: []);
  }

  /// `Please enter your passport number`
  String get pleaseEnterPassport {
    return Intl.message(
      'Please enter your passport number',
      name: 'pleaseEnterPassport',
      desc: '',
      args: [],
    );
  }

  /// `Please enter your password`
  String get pleaseEnterPassword {
    return Intl.message(
      'Please enter your password',
      name: 'pleaseEnterPassword',
      desc: '',
      args: [],
    );
  }

  /// `El Salah`
  String get elSalah {
    return Intl.message('El Salah', name: 'elSalah', desc: '', args: []);
  }

  /// `Time now is `
  String get timeNowIS {
    return Intl.message('Time now is ', name: 'timeNowIS', desc: '', args: []);
  }

  /// `Forget Password`
  String get forgetPasswordTitle {
    return Intl.message(
      'Forget Password',
      name: 'forgetPasswordTitle',
      desc: '',
      args: [],
    );
  }

  /// `Please select another category this didn't contain any implemented yet`
  String get pleaseSelectAntherCategory {
    return Intl.message(
      'Please select another category this didn\'t contain any implemented yet',
      name: 'pleaseSelectAntherCategory',
      desc: '',
      args: [],
    );
  }

  /// `No Reply Yet`
  String get noReply {
    return Intl.message('No Reply Yet', name: 'noReply', desc: '', args: []);
  }

  /// `Select Category`
  String get selectCategory {
    return Intl.message(
      'Select Category',
      name: 'selectCategory',
      desc: '',
      args: [],
    );
  }

  /// `PDF Guide`
  String get pdfGuide {
    return Intl.message('PDF Guide', name: 'pdfGuide', desc: '', args: []);
  }

  /// `Video Guide`
  String get videoGuide {
    return Intl.message('Video Guide', name: 'videoGuide', desc: '', args: []);
  }

  /// `please choose activity`
  String get pleaseChooseActivity {
    return Intl.message(
      'please choose activity',
      name: 'pleaseChooseActivity',
      desc: '',
      args: [],
    );
  }

  /// `please add rate`
  String get pleaseAddRate {
    return Intl.message(
      'please add rate',
      name: 'pleaseAddRate',
      desc: '',
      args: [],
    );
  }

  /// `Sending .....`
  String get sending {
    return Intl.message('Sending .....', name: 'sending', desc: '', args: []);
  }

  /// `password can not Be Empty`
  String get passwordEmpty {
    return Intl.message(
      'password can not Be Empty',
      name: 'passwordEmpty',
      desc: '',
      args: [],
    );
  }

  /// `شركــة مطوفي حجاج تركيا و حجاج أوروبا وأمريكا واستراليا`
  String get splashTitle {
    return Intl.message(
      'شركــة مطوفي حجاج تركيا و حجاج أوروبا وأمريكا واستراليا',
      name: 'splashTitle',
      desc: '',
      args: [],
    );
  }

  /// `Motawifs of Turkey Pilgrims and Pilgrims of Europe, America and Australia Company`
  String get splashSubtitle {
    return Intl.message(
      'Motawifs of Turkey Pilgrims and Pilgrims of Europe, America and Australia Company',
      name: 'splashSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `English`
  String get languageEnglish {
    return Intl.message('English', name: 'languageEnglish', desc: '', args: []);
  }

  /// `Qibla Direction`
  String get qiblaDirection {
    return Intl.message(
      'Qibla Direction',
      name: 'qiblaDirection',
      desc: '',
      args: [],
    );
  }

  /// `Please place the phone on a flat surface  \n to determine the Qibla direction`
  String get pleaseFlatSurface {
    return Intl.message(
      'Please place the phone on a flat surface  \n to determine the Qibla direction',
      name: 'pleaseFlatSurface',
      desc: '',
      args: [],
    );
  }

  /// `Qibla Angle`
  String get angleQibla {
    return Intl.message('Qibla Angle', name: 'angleQibla', desc: '', args: []);
  }

  /// `Device Angle`
  String get angleDevice {
    return Intl.message(
      'Device Angle',
      name: 'angleDevice',
      desc: '',
      args: [],
    );
  }

  /// `العربية`
  String get languageArabic {
    return Intl.message('العربية', name: 'languageArabic', desc: '', args: []);
  }

  /// `Reset Password`
  String get resetPassword {
    return Intl.message(
      'Reset Password',
      name: 'resetPassword',
      desc: '',
      args: [],
    );
  }

  /// `Enter Your E-mail to Reset Your Password`
  String get enterEmailToResetPassword {
    return Intl.message(
      'Enter Your E-mail to Reset Your Password',
      name: 'enterEmailToResetPassword',
      desc: '',
      args: [],
    );
  }

  /// `Enter The Verification Code which sent on Your E-mail`
  String get enterOtp {
    return Intl.message(
      'Enter The Verification Code which sent on Your E-mail',
      name: 'enterOtp',
      desc: '',
      args: [],
    );
  }

  /// `previous`
  String get previous {
    return Intl.message('previous', name: 'previous', desc: '', args: []);
  }

  /// `Enter Pharmaceutical Name`
  String get enterpharmaceuticalName {
    return Intl.message(
      'Enter Pharmaceutical Name',
      name: 'enterpharmaceuticalName',
      desc: '',
      args: [],
    );
  }

  /// `Design & Development`
  String get designDevelopment {
    return Intl.message(
      'Design & Development',
      name: 'designDevelopment',
      desc: '',
      args: [],
    );
  }

  /// `Add Correct Sign At All`
  String get addCorrectSignAtAll {
    return Intl.message(
      'Add Correct Sign At All',
      name: 'addCorrectSignAtAll',
      desc: '',
      args: [],
    );
  }

  /// `Update Data`
  String get updateData {
    return Intl.message('Update Data', name: 'updateData', desc: '', args: []);
  }

  /// `EJAD Digital Solutions co`
  String get ejadDigitalSolutions {
    return Intl.message(
      'EJAD Digital Solutions co',
      name: 'ejadDigitalSolutions',
      desc: '',
      args: [],
    );
  }

  /// `Employee Service Management`
  String get onboarding1Title {
    return Intl.message(
      'Employee Service Management',
      name: 'onboarding1Title',
      desc: '',
      args: [],
    );
  }

  /// `Your personal assistant in your workplace. Consider this your first reference for help. Whether you need to request equipment, review company policies, or get an answer to a question, we provide support at every step.`
  String get onboarding1Desc {
    return Intl.message(
      'Your personal assistant in your workplace. Consider this your first reference for help. Whether you need to request equipment, review company policies, or get an answer to a question, we provide support at every step.',
      name: 'onboarding1Desc',
      desc: '',
      args: [],
    );
  }

  /// `Request Tracking`
  String get onboarding2Title {
    return Intl.message(
      'Request Tracking',
      name: 'onboarding2Title',
      desc: '',
      args: [],
    );
  }

  /// `Support at your fingertips. Get the resources, tools, and assistance you need to succeed. Easily manage your requests and find essential information with ease.`
  String get onboarding2Desc {
    return Intl.message(
      'Support at your fingertips. Get the resources, tools, and assistance you need to succeed. Easily manage your requests and find essential information with ease.',
      name: 'onboarding2Desc',
      desc: '',
      args: [],
    );
  }

  /// `Notifications`
  String get onboarding3Title {
    return Intl.message(
      'Notifications',
      name: 'onboarding3Title',
      desc: '',
      args: [],
    );
  }

  /// `Everything you need, in one place. From IT support and HR requests to office supplies, Employee Service Management is your central hub for getting things done. We handle the logistics so you can focus on your core work.`
  String get onboarding3Desc {
    return Intl.message(
      'Everything you need, in one place. From IT support and HR requests to office supplies, Employee Service Management is your central hub for getting things done. We handle the logistics so you can focus on your core work.',
      name: 'onboarding3Desc',
      desc: '',
      args: [],
    );
  }

  /// `SKIP INTRO`
  String get skipIntro {
    return Intl.message('SKIP INTRO', name: 'skipIntro', desc: '', args: []);
  }

  /// `Continue`
  String get continueButton {
    return Intl.message('Continue', name: 'continueButton', desc: '', args: []);
  }

  /// `Get Started`
  String get getStarted {
    return Intl.message('Get Started', name: 'getStarted', desc: '', args: []);
  }

  /// `This action is irreversible. All your data will be lost, and you will not be able to recover it.`
  String get deleteBody {
    return Intl.message(
      'This action is irreversible. All your data will be lost, and you will not be able to recover it.',
      name: 'deleteBody',
      desc: '',
      args: [],
    );
  }

  /// `Are you sure you want to delete your account?`
  String get askDelete {
    return Intl.message(
      'Are you sure you want to delete your account?',
      name: 'askDelete',
      desc: '',
      args: [],
    );
  }

  /// `Account Deleted`
  String get accountDeleted {
    return Intl.message(
      'Account Deleted',
      name: 'accountDeleted',
      desc: '',
      args: [],
    );
  }

  /// `Request Title`
  String get reqtitle {
    return Intl.message('Request Title', name: 'reqtitle', desc: '', args: []);
  }

  /// `write title here`
  String get writetitleHere {
    return Intl.message(
      'write title here',
      name: 'writetitleHere',
      desc: '',
      args: [],
    );
  }

  /// `Delete My Account`
  String get deleteAccount {
    return Intl.message(
      'Delete My Account',
      name: 'deleteAccount',
      desc: '',
      args: [],
    );
  }

  /// `Feedback Sent Successfully`
  String get feedSend {
    return Intl.message(
      'Feedback Sent Successfully',
      name: 'feedSend',
      desc: '',
      args: [],
    );
  }

  /// `Thanks for sending feedback.`
  String get thankFeed {
    return Intl.message(
      'Thanks for sending feedback.',
      name: 'thankFeed',
      desc: '',
      args: [],
    );
  }

  /// `Send`
  String get send {
    return Intl.message('Send', name: 'send', desc: '', args: []);
  }

  /// `Excellent`
  String get excellent {
    return Intl.message('Excellent', name: 'excellent', desc: '', args: []);
  }

  /// `Poor`
  String get poor {
    return Intl.message('Poor', name: 'poor', desc: '', args: []);
  }

  /// `Good`
  String get good {
    return Intl.message('Good', name: 'good', desc: '', args: []);
  }

  /// `Very Good`
  String get veryGood {
    return Intl.message('Very Good', name: 'veryGood', desc: '', args: []);
  }

  /// `No Rating`
  String get noRating {
    return Intl.message('No Rating', name: 'noRating', desc: '', args: []);
  }

  /// `Profile`
  String get profile {
    return Intl.message('Profile', name: 'profile', desc: '', args: []);
  }

  /// `Al-Kiswa Factory`
  String get factory {
    return Intl.message(
      'Al-Kiswa Factory',
      name: 'factory',
      desc: '',
      args: [],
    );
  }

  /// `Location`
  String get location {
    return Intl.message('Location', name: 'location', desc: '', args: []);
  }

  /// `Guide`
  String get guide {
    return Intl.message('Guide', name: 'guide', desc: '', args: []);
  }

  /// `Prayer Time`
  String get prayer {
    return Intl.message('Prayer Time', name: 'prayer', desc: '', args: []);
  }

  /// `According to the local time of `
  String get according {
    return Intl.message(
      'According to the local time of ',
      name: 'according',
      desc: '',
      args: [],
    );
  }

  /// `The City of Mecca`
  String get city {
    return Intl.message('The City of Mecca', name: 'city', desc: '', args: []);
  }

  /// `Fajr`
  String get fajr {
    return Intl.message('Fajr', name: 'fajr', desc: '', args: []);
  }

  /// `Sunrise`
  String get sunrise {
    return Intl.message('Sunrise', name: 'sunrise', desc: '', args: []);
  }

  /// `Dhuhr`
  String get dhuhr {
    return Intl.message('Dhuhr', name: 'dhuhr', desc: '', args: []);
  }

  /// `write request details here`
  String get writeReqDetailsHere {
    return Intl.message(
      'write request details here',
      name: 'writeReqDetailsHere',
      desc: '',
      args: [],
    );
  }

  /// `Select Department`
  String get selectDepartment {
    return Intl.message(
      'Select Department',
      name: 'selectDepartment',
      desc: '',
      args: [],
    );
  }

  /// `Supervisor Reply :`
  String get supervisorReply {
    return Intl.message(
      'Supervisor Reply :',
      name: 'supervisorReply',
      desc: '',
      args: [],
    );
  }

  /// `Asr`
  String get asr {
    return Intl.message('Asr', name: 'asr', desc: '', args: []);
  }

  /// `Under Process`
  String get underProcess {
    return Intl.message(
      'Under Process',
      name: 'underProcess',
      desc: '',
      args: [],
    );
  }

  /// `Select Luggage`
  String get selectLuggage {
    return Intl.message(
      'Select Luggage',
      name: 'selectLuggage',
      desc: '',
      args: [],
    );
  }

  /// `Tap to select luggage`
  String get tabToSelectLuggage {
    return Intl.message(
      'Tap to select luggage',
      name: 'tabToSelectLuggage',
      desc: '',
      args: [],
    );
  }

  /// `Maghrib`
  String get maghrib {
    return Intl.message('Maghrib', name: 'maghrib', desc: '', args: []);
  }

  /// `Select Issue Department`
  String get selectIssueDep {
    return Intl.message(
      'Select Issue Department',
      name: 'selectIssueDep',
      desc: '',
      args: [],
    );
  }

  /// `Isha`
  String get isha {
    return Intl.message('Isha', name: 'isha', desc: '', args: []);
  }

  /// `Resend Code`
  String get resend {
    return Intl.message('Resend Code', name: 'resend', desc: '', args: []);
  }

  /// `Confirm `
  String get confirm {
    return Intl.message('Confirm ', name: 'confirm', desc: '', args: []);
  }

  /// `Success`
  String get success {
    return Intl.message('Success', name: 'success', desc: '', args: []);
  }

  /// `Cancel`
  String get cancel {
    return Intl.message('Cancel', name: 'cancel', desc: '', args: []);
  }

  /// `Phone number is required`
  String get phoneRequired {
    return Intl.message(
      'Phone number is required',
      name: 'phoneRequired',
      desc: '',
      args: [],
    );
  }

  /// `OTP code sent successfully`
  String get otpSent {
    return Intl.message(
      'OTP code sent successfully',
      name: 'otpSent',
      desc: '',
      args: [],
    );
  }

  /// `Invalid OTP code`
  String get invalidOtp {
    return Intl.message(
      'Invalid OTP code',
      name: 'invalidOtp',
      desc: '',
      args: [],
    );
  }

  /// `verify OTP`
  String get sendOtp {
    return Intl.message('verify OTP', name: 'sendOtp', desc: '', args: []);
  }

  /// `OTP`
  String get otp {
    return Intl.message('OTP', name: 'otp', desc: '', args: []);
  }

  /// `Back`
  String get back {
    return Intl.message('Back', name: 'back', desc: '', args: []);
  }

  /// `New Password`
  String get newPassword {
    return Intl.message(
      'New Password',
      name: 'newPassword',
      desc: '',
      args: [],
    );
  }

  /// `Repeat New Password`
  String get repeatNewPassword {
    return Intl.message(
      'Repeat New Password',
      name: 'repeatNewPassword',
      desc: '',
      args: [],
    );
  }

  /// `Save New Password`
  String get saveNewPassword {
    return Intl.message(
      'Save New Password',
      name: 'saveNewPassword',
      desc: '',
      args: [],
    );
  }

  /// `Create New Password`
  String get createNewPassword {
    return Intl.message(
      'Create New Password',
      name: 'createNewPassword',
      desc: '',
      args: [],
    );
  }

  /// `Orders`
  String get myOrders {
    return Intl.message('Orders', name: 'myOrders', desc: '', args: []);
  }

  /// `services`
  String get services {
    return Intl.message('services', name: 'services', desc: '', args: []);
  }

  /// `Leave Request`
  String get leaveRequest {
    return Intl.message(
      'Leave Request',
      name: 'leaveRequest',
      desc: '',
      args: [],
    );
  }

  /// `Overtime Request`
  String get overtimeRequest {
    return Intl.message(
      'Overtime Request',
      name: 'overtimeRequest',
      desc: '',
      args: [],
    );
  }

  /// `Fingerprint Verification Request`
  String get fingerprintProofRequest {
    return Intl.message(
      'Fingerprint Verification Request',
      name: 'fingerprintProofRequest',
      desc: '',
      args: [],
    );
  }

  /// `Exit Permission Request`
  String get exitPermissionRequest {
    return Intl.message(
      'Exit Permission Request',
      name: 'exitPermissionRequest',
      desc: '',
      args: [],
    );
  }

  /// `School Permission Request`
  String get schoolPermissionRequest {
    return Intl.message(
      'School Permission Request',
      name: 'schoolPermissionRequest',
      desc: '',
      args: [],
    );
  }

  /// `Car Permit Request`
  String get carPermitRequest {
    return Intl.message(
      'Car Permit Request',
      name: 'carPermitRequest',
      desc: '',
      args: [],
    );
  }

  /// `During working hours: leaving and returning on the same day (quick medical appointment).`
  String get duringWorkHours {
    return Intl.message(
      'During working hours: leaving and returning on the same day (quick medical appointment).',
      name: 'duringWorkHours',
      desc: '',
      args: [],
    );
  }

  /// `End of Service Request`
  String get endOfServiceRequest {
    return Intl.message(
      'End of Service Request',
      name: 'endOfServiceRequest',
      desc: '',
      args: [],
    );
  }

  /// `Clearance Request`
  String get clearanceRequest {
    return Intl.message(
      'Clearance Request',
      name: 'clearanceRequest',
      desc: '',
      args: [],
    );
  }

  /// `ID Renewal Request`
  String get idRenewalRequest {
    return Intl.message(
      'ID Renewal Request',
      name: 'idRenewalRequest',
      desc: '',
      args: [],
    );
  }

  /// `Exit Permission Request`
  String get exitPermissionTitle {
    return Intl.message(
      'Exit Permission Request',
      name: 'exitPermissionTitle',
      desc: '',
      args: [],
    );
  }

  /// `When and Why It Is Used`
  String get whenAndWhyUsed {
    return Intl.message(
      'When and Why It Is Used',
      name: 'whenAndWhyUsed',
      desc: '',
      args: [],
    );
  }

  /// `Exit permission is used in cases where an employee needs to be outside the workplace during official working hours.\n\nDuring working hours: leaving and returning on the same day (a quick medical appointment).\n\nBefore the end of the workday: leaving without returning (early departure).\n\nExternal assignments: if the employee is assigned a task that requires being present at another location.`
  String get whenAndWhyDescription {
    return Intl.message(
      'Exit permission is used in cases where an employee needs to be outside the workplace during official working hours.\n\nDuring working hours: leaving and returning on the same day (a quick medical appointment).\n\nBefore the end of the workday: leaving without returning (early departure).\n\nExternal assignments: if the employee is assigned a task that requires being present at another location.',
      name: 'whenAndWhyDescription',
      desc: '',
      args: [],
    );
  }

  /// `Exit permission is used when an employee needs to be outside the workplace during official working hours.`
  String get exitPermissionDescription {
    return Intl.message(
      'Exit permission is used when an employee needs to be outside the workplace during official working hours.',
      name: 'exitPermissionDescription',
      desc: '',
      args: [],
    );
  }

  /// `Before the end of the workday: leaving without returning (early departure).`
  String get beforeEndOfDay {
    return Intl.message(
      'Before the end of the workday: leaving without returning (early departure).',
      name: 'beforeEndOfDay',
      desc: '',
      args: [],
    );
  }

  /// `External assignments: when an employee is assigned a task that requires being at another location.`
  String get externalMission {
    return Intl.message(
      'External assignments: when an employee is assigned a task that requires being at another location.',
      name: 'externalMission',
      desc: '',
      args: [],
    );
  }

  /// `Allowed Duration / Rules`
  String get allowedDurationRules {
    return Intl.message(
      'Allowed Duration / Rules',
      name: 'allowedDurationRules',
      desc: '',
      args: [],
    );
  }

  /// `Processing Type: Immediate / Requires Approval`
  String get processingType {
    return Intl.message(
      'Processing Type: Immediate / Requires Approval',
      name: 'processingType',
      desc: '',
      args: [],
    );
  }

  /// `Service Level Agreement / Expected Response Time`
  String get slaResponseTime {
    return Intl.message(
      'Service Level Agreement / Expected Response Time',
      name: 'slaResponseTime',
      desc: '',
      args: [],
    );
  }

  /// `Create Request`
  String get createRequest {
    return Intl.message(
      'Create Request',
      name: 'createRequest',
      desc: '',
      args: [],
    );
  }

  /// `date`
  String get date {
    return Intl.message('date', name: 'date', desc: '', args: []);
  }

  /// `Order Date`
  String get orderDate {
    return Intl.message('Order Date', name: 'orderDate', desc: '', args: []);
  }

  /// `Order Status`
  String get orderStatus {
    return Intl.message(
      'Order Status',
      name: 'orderStatus',
      desc: '',
      args: [],
    );
  }

  /// `Login with Phone`
  String get loginWithPhone {
    return Intl.message(
      'Login with Phone',
      name: 'loginWithPhone',
      desc: '',
      args: [],
    );
  }

  /// `Enter your phone number to receive verification code`
  String get enterPhoneDescription {
    return Intl.message(
      'Enter your phone number to receive verification code',
      name: 'enterPhoneDescription',
      desc: '',
      args: [],
    );
  }

  /// `Enter phone number`
  String get enterPhoneNumber {
    return Intl.message(
      'Enter phone number',
      name: 'enterPhoneNumber',
      desc: '',
      args: [],
    );
  }

  /// `Congratulation`
  String get congrats {
    return Intl.message('Congratulation', name: 'congrats', desc: '', args: []);
  }

  /// `Thanks for completing your profile. Now you can use app`
  String get thanksProfile {
    return Intl.message(
      'Thanks for completing your profile. Now you can use app',
      name: 'thanksProfile',
      desc: '',
      args: [],
    );
  }

  /// `Go to Home`
  String get go {
    return Intl.message('Go to Home', name: 'go', desc: '', args: []);
  }

  /// `Request Sent Successfully`
  String get requestSend {
    return Intl.message(
      'Request Sent Successfully',
      name: 'requestSend',
      desc: '',
      args: [],
    );
  }

  /// `Thanks for sending request, and will\nrespond ASAP`
  String get thanksRequest {
    return Intl.message(
      'Thanks for sending request, and will\nrespond ASAP',
      name: 'thanksRequest',
      desc: '',
      args: [],
    );
  }

  /// `Add New Email`
  String get addEmail {
    return Intl.message('Add New Email', name: 'addEmail', desc: '', args: []);
  }

  /// `Personal Email`
  String get personalEmail {
    return Intl.message(
      'Personal Email',
      name: 'personalEmail',
      desc: '',
      args: [],
    );
  }

  /// `Type your Personal Email`
  String get typePersonal {
    return Intl.message(
      'Type your Personal Email',
      name: 'typePersonal',
      desc: '',
      args: [],
    );
  }

  /// `Confirm Email`
  String get confirmEmail {
    return Intl.message(
      'Confirm Email',
      name: 'confirmEmail',
      desc: '',
      args: [],
    );
  }

  /// `Add New Password`
  String get addPassword {
    return Intl.message(
      'Add New Password',
      name: 'addPassword',
      desc: '',
      args: [],
    );
  }

  /// `New Password`
  String get newPass {
    return Intl.message('New Password', name: 'newPass', desc: '', args: []);
  }

  /// `Confirm Password`
  String get confirmPass {
    return Intl.message(
      'Confirm Password',
      name: 'confirmPass',
      desc: '',
      args: [],
    );
  }

  /// `Login`
  String get login {
    return Intl.message('Login', name: 'login', desc: '', args: []);
  }

  /// `Your request has been sent successfully`
  String get requestSentSuccessfully {
    return Intl.message(
      'Your request has been sent successfully',
      name: 'requestSentSuccessfully',
      desc: '',
      args: [],
    );
  }

  /// `Passport No.`
  String get passNo {
    return Intl.message('Passport No.', name: 'passNo', desc: '', args: []);
  }

  /// `About the App`
  String get aboutApp {
    return Intl.message('About the App', name: 'aboutApp', desc: '', args: []);
  }

  /// `Privacy Policy`
  String get privacyPolicy {
    return Intl.message(
      'Privacy Policy',
      name: 'privacyPolicy',
      desc: '',
      args: [],
    );
  }

  /// `User Policy`
  String get userPolicy {
    return Intl.message('User Policy', name: 'userPolicy', desc: '', args: []);
  }

  /// `App Language`
  String get appLanguage {
    return Intl.message(
      'App Language',
      name: 'appLanguage',
      desc: '',
      args: [],
    );
  }

  /// `Type your Passport Number`
  String get typePass {
    return Intl.message(
      'Type your Passport Number',
      name: 'typePass',
      desc: '',
      args: [],
    );
  }

  /// `Password`
  String get password {
    return Intl.message('Password', name: 'password', desc: '', args: []);
  }

  /// `Request applicant Data`
  String get requestApplicantData {
    return Intl.message(
      'Request applicant Data',
      name: 'requestApplicantData',
      desc: '',
      args: [],
    );
  }

  /// `Applicant`
  String get applicantName {
    return Intl.message('Applicant', name: 'applicantName', desc: '', args: []);
  }

  /// `Organizational Unit`
  String get organizationalUnit {
    return Intl.message(
      'Organizational Unit',
      name: 'organizationalUnit',
      desc: '',
      args: [],
    );
  }

  /// `Download Attachment`
  String get downloadAttachment {
    return Intl.message(
      'Download Attachment',
      name: 'downloadAttachment',
      desc: '',
      args: [],
    );
  }

  /// `Request Details`
  String get requestDetails {
    return Intl.message(
      'Request Details',
      name: 'requestDetails',
      desc: '',
      args: [],
    );
  }

  /// `Permission Date`
  String get permissionDate {
    return Intl.message(
      'Permission Date',
      name: 'permissionDate',
      desc: '',
      args: [],
    );
  }

  /// `Permission Time`
  String get permissionTime {
    return Intl.message(
      'Permission Time',
      name: 'permissionTime',
      desc: '',
      args: [],
    );
  }

  /// `Duration (Hours)`
  String get durationInHours {
    return Intl.message(
      'Duration (Hours)',
      name: 'durationInHours',
      desc: '',
      args: [],
    );
  }

  /// `Submit Request`
  String get submitRequest {
    return Intl.message(
      'Submit Request',
      name: 'submitRequest',
      desc: '',
      args: [],
    );
  }

  /// `Forget Password ?`
  String get forget {
    return Intl.message(
      'Forget Password ?',
      name: 'forget',
      desc: '',
      args: [],
    );
  }

  /// `Verified Account`
  String get verifiedAccount {
    return Intl.message(
      'Verified Account',
      name: 'verifiedAccount',
      desc: '',
      args: [],
    );
  }

  /// `Date of Brith`
  String get dateBirth {
    return Intl.message('Date of Brith', name: 'dateBirth', desc: '', args: []);
  }

  /// `MM-DD-YYYY`
  String get mm {
    return Intl.message('MM-DD-YYYY', name: 'mm', desc: '', args: []);
  }

  /// `Verify Account`
  String get verify {
    return Intl.message('Verify Account', name: 'verify', desc: '', args: []);
  }

  /// `Supervisor:`
  String get supervisor {
    return Intl.message('Supervisor:', name: 'supervisor', desc: '', args: []);
  }

  /// `Mostafa Zakaria`
  String get mostafa {
    return Intl.message('Mostafa Zakaria', name: 'mostafa', desc: '', args: []);
  }

  /// `Activities`
  String get activities {
    return Intl.message('Activities', name: 'activities', desc: '', args: []);
  }

  /// `Kaaba Tawaf`
  String get kaaba {
    return Intl.message('Kaaba Tawaf', name: 'kaaba', desc: '', args: []);
  }

  /// `05/17/2024  06:30PM`
  String get date05 {
    return Intl.message(
      '05/17/2024  06:30PM',
      name: 'date05',
      desc: '',
      args: [],
    );
  }

  /// `Completed`
  String get completed {
    return Intl.message('Completed', name: 'completed', desc: '', args: []);
  }

  /// `Perform SA'I`
  String get perform {
    return Intl.message('Perform SA\'I', name: 'perform', desc: '', args: []);
  }

  /// `Depart to Mina`
  String get depart {
    return Intl.message('Depart to Mina', name: 'depart', desc: '', args: []);
  }

  /// `Stoning Devil in Mina`
  String get stoning {
    return Intl.message(
      'Stoning Devil in Mina',
      name: 'stoning',
      desc: '',
      args: [],
    );
  }

  /// `Main Group`
  String get mainGroup {
    return Intl.message('Main Group', name: 'mainGroup', desc: '', args: []);
  }

  /// `Group A`
  String get groupA {
    return Intl.message('Group A', name: 'groupA', desc: '', args: []);
  }

  /// `Sub Groups`
  String get subGroup {
    return Intl.message('Sub Groups', name: 'subGroup', desc: '', args: []);
  }

  /// `Group A-1`
  String get grA1 {
    return Intl.message('Group A-1', name: 'grA1', desc: '', args: []);
  }

  /// `Group B-3`
  String get grB3 {
    return Intl.message('Group B-3', name: 'grB3', desc: '', args: []);
  }

  /// `Group B-6`
  String get grB6 {
    return Intl.message('Group B-6', name: 'grB6', desc: '', args: []);
  }

  /// `Abdelsalam Sleim`
  String get abd {
    return Intl.message('Abdelsalam Sleim', name: 'abd', desc: '', args: []);
  }

  /// `Hajj Activity`
  String get hajj {
    return Intl.message('Hajj Activity', name: 'hajj', desc: '', args: []);
  }

  /// `In Progress`
  String get inProgress {
    return Intl.message('In Progress', name: 'inProgress', desc: '', args: []);
  }

  /// `Upcoming`
  String get upcoming {
    return Intl.message('Upcoming', name: 'upcoming', desc: '', args: []);
  }

  /// `Visit Al-Kiswa Factory`
  String get visitFactory {
    return Intl.message(
      'Visit Al-Kiswa Factory',
      name: 'visitFactory',
      desc: '',
      args: [],
    );
  }

  /// `Visit Riyadh Festival`
  String get visitFestival {
    return Intl.message(
      'Visit Riyadh Festival',
      name: 'visitFestival',
      desc: '',
      args: [],
    );
  }

  /// `Retry`
  String get retry {
    return Intl.message('Retry', name: 'retry', desc: '', args: []);
  }

  /// `Home`
  String get home {
    return Intl.message('Home', name: 'home', desc: '', args: []);
  }

  /// `change`
  String get change {
    return Intl.message('change', name: 'change', desc: '', args: []);
  }

  /// `Package: `
  String get package {
    return Intl.message('Package: ', name: 'package', desc: '', args: []);
  }

  /// `Service Office:  165`
  String get service {
    return Intl.message(
      'Service Office:  165',
      name: 'service',
      desc: '',
      args: [],
    );
  }

  /// `Group:  Group A`
  String get gr {
    return Intl.message('Group:  Group A', name: 'gr', desc: '', args: []);
  }

  /// `Supervisor:  Abdelsalam Sleim`
  String get srAbd {
    return Intl.message(
      'Supervisor:  Abdelsalam Sleim',
      name: 'srAbd',
      desc: '',
      args: [],
    );
  }

  /// `Current Events`
  String get currentEvent {
    return Intl.message(
      'Current Events',
      name: 'currentEvent',
      desc: '',
      args: [],
    );
  }

  /// `Hajj & Umrah`
  String get hu {
    return Intl.message('Hajj & Umrah', name: 'hu', desc: '', args: []);
  }

  /// `Enrichment`
  String get enr {
    return Intl.message('Enrichment', name: 'enr', desc: '', args: []);
  }

  /// `Today Notifications`
  String get today {
    return Intl.message(
      'Today Notifications',
      name: 'today',
      desc: '',
      args: [],
    );
  }

  /// `Today`
  String get filterToday {
    return Intl.message('Today', name: 'filterToday', desc: '', args: []);
  }

  /// `Show All`
  String get show {
    return Intl.message('Show All', name: 'show', desc: '', args: []);
  }

  /// `Lunch time is approaching, Be prepared!`
  String get lunchTime {
    return Intl.message(
      'Lunch time is approaching, Be prepared!',
      name: 'lunchTime',
      desc: '',
      args: [],
    );
  }

  /// `will be ready in 15 minutes.`
  String get will {
    return Intl.message(
      'will be ready in 15 minutes.',
      name: 'will',
      desc: '',
      args: [],
    );
  }

  /// `Sent in: 15/4/2024 03:45 pm`
  String get sentIn {
    return Intl.message(
      'Sent in: 15/4/2024 03:45 pm',
      name: 'sentIn',
      desc: '',
      args: [],
    );
  }

  /// `Meals`
  String get meals {
    return Intl.message('Meals', name: 'meals', desc: '', args: []);
  }

  /// `Kaaba Tawaf Activity`
  String get kaabaActivity {
    return Intl.message(
      'Kaaba Tawaf Activity',
      name: 'kaabaActivity',
      desc: '',
      args: [],
    );
  }

  /// `Kaaba Tawaf activity will start within 30 minutes`
  String get tawafStart {
    return Intl.message(
      'Kaaba Tawaf activity will start within 30 minutes',
      name: 'tawafStart',
      desc: '',
      args: [],
    );
  }

  /// `Other Features`
  String get other {
    return Intl.message('Other Features', name: 'other', desc: '', args: []);
  }

  /// `Prayer Time  11:52 AM`
  String get prayerIn {
    return Intl.message(
      'Prayer Time  11:52 AM',
      name: 'prayerIn',
      desc: '',
      args: [],
    );
  }

  /// `Residence Location`
  String get resLocation {
    return Intl.message(
      'Residence Location',
      name: 'resLocation',
      desc: '',
      args: [],
    );
  }

  /// `Residence`
  String get residence {
    return Intl.message('Residence', name: 'residence', desc: '', args: []);
  }

  /// `Quran`
  String get quran {
    return Intl.message('Quran', name: 'quran', desc: '', args: []);
  }

  /// `Karim`
  String get karim {
    return Intl.message('Karim', name: 'karim', desc: '', args: []);
  }

  /// `Qibla`
  String get qibla {
    return Intl.message('Qibla', name: 'qibla', desc: '', args: []);
  }

  /// `compass`
  String get compass {
    return Intl.message('compass', name: 'compass', desc: '', args: []);
  }

  /// `Notifications`
  String get notification {
    return Intl.message(
      'Notifications',
      name: 'notification',
      desc: '',
      args: [],
    );
  }

  /// `Mark all as read`
  String get markAll {
    return Intl.message(
      'Mark all as read',
      name: 'markAll',
      desc: '',
      args: [],
    );
  }

  /// `Meal`
  String get meal {
    return Intl.message('Meal', name: 'meal', desc: '', args: []);
  }

  /// `Lost My Bags in Airport`
  String get lostBag {
    return Intl.message(
      'Lost My Bags in Airport',
      name: 'lostBag',
      desc: '',
      args: [],
    );
  }

  /// `Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con`
  String get ut {
    return Intl.message(
      'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con',
      name: 'ut',
      desc: '',
      args: [],
    );
  }

  /// `Support`
  String get support {
    return Intl.message('Support', name: 'support', desc: '', args: []);
  }

  /// `Rotate Device`
  String get rotate {
    return Intl.message('Rotate Device', name: 'rotate', desc: '', args: []);
  }

  /// `To Get Qibla Direction`
  String get direction {
    return Intl.message(
      'To Get Qibla Direction',
      name: 'direction',
      desc: '',
      args: [],
    );
  }

  /// `Location service permission denied`
  String get locSer {
    return Intl.message(
      'Location service permission denied',
      name: 'locSer',
      desc: '',
      args: [],
    );
  }

  /// `Location service Denied Forever !`
  String get loc {
    return Intl.message(
      'Location service Denied Forever !',
      name: 'loc',
      desc: '',
      args: [],
    );
  }

  /// `Please enable Location service`
  String get please {
    return Intl.message(
      'Please enable Location service',
      name: 'please',
      desc: '',
      args: [],
    );
  }

  /// `Name:`
  String get name {
    return Intl.message('Name:', name: 'name', desc: '', args: []);
  }

  /// `Quran Hafs By KFGQPC`
  String get quranHafs {
    return Intl.message(
      'Quran Hafs By KFGQPC',
      name: 'quranHafs',
      desc: '',
      args: [],
    );
  }

  /// `ProvideBy:`
  String get provide {
    return Intl.message('ProvideBy:', name: 'provide', desc: '', args: []);
  }

  /// `King Fhad Glorious Quran Printing Complex`
  String get fahd {
    return Intl.message(
      'King Fhad Glorious Quran Printing Complex',
      name: 'fahd',
      desc: '',
      args: [],
    );
  }

  /// `Description:`
  String get desc {
    return Intl.message('Description:', name: 'desc', desc: '', args: []);
  }

  /// `Produced by King Fhad Glorious Quran Printing Complex in Al-Madina Al-Munawwara`
  String get produce {
    return Intl.message(
      'Produced by King Fhad Glorious Quran Printing Complex in Al-Madina Al-Munawwara',
      name: 'produce',
      desc: '',
      args: [],
    );
  }

  /// `Open Google Map`
  String get open {
    return Intl.message('Open Google Map', name: 'open', desc: '', args: []);
  }

  /// `   Safwa Hotel`
  String get saf {
    return Intl.message('   Safwa Hotel', name: 'saf', desc: '', args: []);
  }

  /// `Type:`
  String get type {
    return Intl.message('Type:', name: 'type', desc: '', args: []);
  }

  /// `   Location Residence`
  String get loRe {
    return Intl.message(
      '   Location Residence',
      name: 'loRe',
      desc: '',
      args: [],
    );
  }

  /// `   Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu`
  String get duis {
    return Intl.message(
      '   Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu',
      name: 'duis',
      desc: '',
      args: [],
    );
  }

  /// `KAABAH TAWAF`
  String get kaabahtawaf {
    return Intl.message(
      'KAABAH TAWAF',
      name: 'kaabahtawaf',
      desc: '',
      args: [],
    );
  }

  /// `Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu`
  String get onboardingDes {
    return Intl.message(
      'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu',
      name: 'onboardingDes',
      desc: '',
      args: [],
    );
  }

  /// `English`
  String get english {
    return Intl.message('English', name: 'english', desc: '', args: []);
  }

  /// `Arabic`
  String get arabic {
    return Intl.message('Arabic', name: 'arabic', desc: '', args: []);
  }

  /// `Groups`
  String get groups {
    return Intl.message('Groups', name: 'groups', desc: '', args: []);
  }

  /// `Residencies`
  String get res {
    return Intl.message('Residencies', name: 'res', desc: '', args: []);
  }

  /// `QR Code`
  String get qr {
    return Intl.message('QR Code', name: 'qr', desc: '', args: []);
  }

  /// `FAQ`
  String get faq {
    return Intl.message('FAQ', name: 'faq', desc: '', args: []);
  }

  /// `Feedback`
  String get feed {
    return Intl.message('Feedback', name: 'feed', desc: '', args: []);
  }

  /// `Logout`
  String get logout {
    return Intl.message('Logout', name: 'logout', desc: '', args: []);
  }

  /// `Design & Development By`
  String get design {
    return Intl.message(
      'Design & Development By',
      name: 'design',
      desc: '',
      args: [],
    );
  }

  /// `Ejad Digital Solutions co`
  String get ejad {
    return Intl.message(
      'Ejad Digital Solutions co',
      name: 'ejad',
      desc: '',
      args: [],
    );
  }

  /// `Foul and Falafel`
  String get foul {
    return Intl.message('Foul and Falafel', name: 'foul', desc: '', args: []);
  }

  /// `15/04/2024`
  String get date15 {
    return Intl.message('15/04/2024', name: 'date15', desc: '', args: []);
  }

  /// `09:30 AM`
  String get time09 {
    return Intl.message('09:30 AM', name: 'time09', desc: '', args: []);
  }

  /// `Menu`
  String get menu {
    return Intl.message('Menu', name: 'menu', desc: '', args: []);
  }

  /// `Egyptian`
  String get egy {
    return Intl.message('Egyptian', name: 'egy', desc: '', args: []);
  }

  /// `2 falafel sandwiches + 1 fava beans sandwich + 1 egg + 1 green salad`
  String get fal {
    return Intl.message(
      '2 falafel sandwiches + 1 fava beans sandwich + 1 egg + 1 green salad',
      name: 'fal',
      desc: '',
      args: [],
    );
  }

  /// `Omelets Eggs`
  String get omlet {
    return Intl.message('Omelets Eggs', name: 'omlet', desc: '', args: []);
  }

  /// `Open Buffet`
  String get buffet {
    return Intl.message('Open Buffet', name: 'buffet', desc: '', args: []);
  }

  /// `Asian`
  String get asian {
    return Intl.message('Asian', name: 'asian', desc: '', args: []);
  }

  /// `Omelets eggs + green salad + Bread`
  String get eggs {
    return Intl.message(
      'Omelets eggs + green salad + Bread',
      name: 'eggs',
      desc: '',
      args: [],
    );
  }

  /// `Rectangle 44`
  String get rec {
    return Intl.message('Rectangle 44', name: 'rec', desc: '', args: []);
  }

  /// `Lunch`
  String get lun {
    return Intl.message('Lunch', name: 'lun', desc: '', args: []);
  }

  /// `Date: `
  String get da {
    return Intl.message('Date: ', name: 'da', desc: '', args: []);
  }

  /// `Time: `
  String get time {
    return Intl.message('Time: ', name: 'time', desc: '', args: []);
  }

  /// `Category: `
  String get cat {
    return Intl.message('Category: ', name: 'cat', desc: '', args: []);
  }

  /// `Type: `
  String get ty {
    return Intl.message('Type: ', name: 'ty', desc: '', args: []);
  }

  /// `Meal Components `
  String get meCo {
    return Intl.message('Meal Components ', name: 'meCo', desc: '', args: []);
  }

  /// `Main Meals`
  String get mainMeal {
    return Intl.message('Main Meals', name: 'mainMeal', desc: '', args: []);
  }

  /// `Activities Meals`
  String get actMeal {
    return Intl.message(
      'Activities Meals',
      name: 'actMeal',
      desc: '',
      args: [],
    );
  }

  /// `Arrival & Departure Info. `
  String get arrDe {
    return Intl.message(
      'Arrival & Departure Info. ',
      name: 'arrDe',
      desc: '',
      args: [],
    );
  }

  /// `Arrival Flight No.`
  String get arrFli {
    return Intl.message(
      'Arrival Flight No.',
      name: 'arrFli',
      desc: '',
      args: [],
    );
  }

  /// `Arrival Date`
  String get arrDate {
    return Intl.message('Arrival Date', name: 'arrDate', desc: '', args: []);
  }

  /// `mm/dd/yyyy  hh:mm`
  String get hh {
    return Intl.message('mm/dd/yyyy  hh:mm', name: 'hh', desc: '', args: []);
  }

  /// `City of Residence ( Arrival )`
  String get cityAr {
    return Intl.message(
      'City of Residence ( Arrival )',
      name: 'cityAr',
      desc: '',
      args: [],
    );
  }

  /// `select arrival city`
  String get selectArr {
    return Intl.message(
      'select arrival city',
      name: 'selectArr',
      desc: '',
      args: [],
    );
  }

  /// `Departure Flight No.`
  String get depFli {
    return Intl.message(
      'Departure Flight No.',
      name: 'depFli',
      desc: '',
      args: [],
    );
  }

  /// `Departure Date`
  String get depDate {
    return Intl.message('Departure Date', name: 'depDate', desc: '', args: []);
  }

  /// `City of Residence ( Departure )`
  String get cityDep {
    return Intl.message(
      'City of Residence ( Departure )',
      name: 'cityDep',
      desc: '',
      args: [],
    );
  }

  /// `select departure city`
  String get selectDep {
    return Intl.message(
      'select departure city',
      name: 'selectDep',
      desc: '',
      args: [],
    );
  }

  /// `Luggage`
  String get luggage {
    return Intl.message('Luggage', name: 'luggage', desc: '', args: []);
  }

  /// ` Add New`
  String get addNew {
    return Intl.message(' Add New', name: 'addNew', desc: '', args: []);
  }

  /// `Description`
  String get des {
    return Intl.message('Description', name: 'des', desc: '', args: []);
  }

  /// `enter a description of bag`
  String get enterDes {
    return Intl.message(
      'enter a description of bag',
      name: 'enterDes',
      desc: '',
      args: [],
    );
  }

  /// `Image`
  String get image {
    return Intl.message('Image', name: 'image', desc: '', args: []);
  }

  /// `Personal Information`
  String get perInfo {
    return Intl.message(
      'Personal Information',
      name: 'perInfo',
      desc: '',
      args: [],
    );
  }

  /// `Full Name`
  String get full {
    return Intl.message('Full Name', name: 'full', desc: '', args: []);
  }

  /// `write your name here`
  String get writeNAme {
    return Intl.message(
      'write your name here',
      name: 'writeNAme',
      desc: '',
      args: [],
    );
  }

  /// `Gender`
  String get gender {
    return Intl.message('Gender', name: 'gender', desc: '', args: []);
  }

  /// `Male`
  String get male {
    return Intl.message('Male', name: 'male', desc: '', args: []);
  }

  /// `Female`
  String get female {
    return Intl.message('Female', name: 'female', desc: '', args: []);
  }

  /// `Nationality`
  String get nation {
    return Intl.message('Nationality', name: 'nation', desc: '', args: []);
  }

  /// `Select Nationality`
  String get selectNation {
    return Intl.message(
      'Select Nationality',
      name: 'selectNation',
      desc: '',
      args: [],
    );
  }

  /// `Visa No.`
  String get visaNo {
    return Intl.message('Visa No.', name: 'visaNo', desc: '', args: []);
  }

  /// `write your visa number`
  String get writeVisa {
    return Intl.message(
      'write your visa number',
      name: 'writeVisa',
      desc: '',
      args: [],
    );
  }

  /// `Account Details`
  String get accDetails {
    return Intl.message(
      'Account Details',
      name: 'accDetails',
      desc: '',
      args: [],
    );
  }

  /// `write email address`
  String get writeEmail {
    return Intl.message(
      'write email address',
      name: 'writeEmail',
      desc: '',
      args: [],
    );
  }

  /// `Mobile No`
  String get mob {
    return Intl.message('Mobile No', name: 'mob', desc: '', args: []);
  }

  /// `write mobile number`
  String get writeMob {
    return Intl.message(
      'write mobile number',
      name: 'writeMob',
      desc: '',
      args: [],
    );
  }

  /// `Done`
  String get done {
    return Intl.message('Done', name: 'done', desc: '', args: []);
  }

  /// `Skip`
  String get sk {
    return Intl.message('Skip', name: 'sk', desc: '', args: []);
  }

  /// `General`
  String get general {
    return Intl.message('General', name: 'general', desc: '', args: []);
  }

  /// `Arrival`
  String get arrival {
    return Intl.message('Arrival', name: 'arrival', desc: '', args: []);
  }

  /// `Medical`
  String get medical {
    return Intl.message('Medical', name: 'medical', desc: '', args: []);
  }

  /// `Allergies`
  String get allergies {
    return Intl.message('Allergies', name: 'allergies', desc: '', args: []);
  }

  /// `Allergies Problems`
  String get allergiesPr {
    return Intl.message(
      'Allergies Problems',
      name: 'allergiesPr',
      desc: '',
      args: [],
    );
  }

  /// `write if you have any allergies problems`
  String get writeAllergies {
    return Intl.message(
      'write if you have any allergies problems',
      name: 'writeAllergies',
      desc: '',
      args: [],
    );
  }

  /// `Diseases`
  String get diseases {
    return Intl.message('Diseases', name: 'diseases', desc: '', args: []);
  }

  /// `Diseases Problems`
  String get diseasesPr {
    return Intl.message(
      'Diseases Problems',
      name: 'diseasesPr',
      desc: '',
      args: [],
    );
  }

  /// `write if you have any diseases problems`
  String get diseasesWrite {
    return Intl.message(
      'write if you have any diseases problems',
      name: 'diseasesWrite',
      desc: '',
      args: [],
    );
  }

  /// `Pharmaceutical`
  String get pharma {
    return Intl.message('Pharmaceutical', name: 'pharma', desc: '', args: []);
  }

  /// `Dose user need any special assistance ?`
  String get does {
    return Intl.message(
      'Dose user need any special assistance ?',
      name: 'does',
      desc: '',
      args: [],
    );
  }

  /// `Yes`
  String get yes {
    return Intl.message('Yes', name: 'yes', desc: '', args: []);
  }

  /// `No`
  String get no {
    return Intl.message('No', name: 'no', desc: '', args: []);
  }

  /// `Mena Camps`
  String get mena {
    return Intl.message('Mena Camps', name: 'mena', desc: '', args: []);
  }

  /// `Hajj - Mena`
  String get hm {
    return Intl.message('Hajj - Mena', name: 'hm', desc: '', args: []);
  }

  /// `Arafa Camps`
  String get ac {
    return Intl.message('Arafa Camps', name: 'ac', desc: '', args: []);
  }

  /// `Hajj - Arafa`
  String get ha {
    return Intl.message('Hajj - Arafa', name: 'ha', desc: '', args: []);
  }

  /// `Hajj & Umrah Guide`
  String get hug {
    return Intl.message('Hajj & Umrah Guide', name: 'hug', desc: '', args: []);
  }

  /// `Hajj & Umrah Video Guide`
  String get huv {
    return Intl.message(
      'Hajj & Umrah Video Guide',
      name: 'huv',
      desc: '',
      args: [],
    );
  }

  /// `View`
  String get view {
    return Intl.message('View', name: 'view', desc: '', args: []);
  }

  /// `Safwa Hotel`
  String get safwa {
    return Intl.message('Safwa Hotel', name: 'safwa', desc: '', args: []);
  }

  /// `Makkah`
  String get makkah {
    return Intl.message('Makkah', name: 'makkah', desc: '', args: []);
  }

  /// `Hilton Hotel`
  String get hilton {
    return Intl.message('Hilton Hotel', name: 'hilton', desc: '', args: []);
  }

  /// `Residences`
  String get resi {
    return Intl.message('Residences', name: 'resi', desc: '', args: []);
  }

  /// `Search here`
  String get search {
    return Intl.message('Search here', name: 'search', desc: '', args: []);
  }

  /// `How I use app`
  String get how {
    return Intl.message('How I use app', name: 'how', desc: '', args: []);
  }

  /// `Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con \n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu`
  String get enimad {
    return Intl.message(
      'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo con \\n\\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatu',
      name: 'enimad',
      desc: '',
      args: [],
    );
  }

  /// `Residency`
  String get dency {
    return Intl.message('Residency', name: 'dency', desc: '', args: []);
  }

  /// `Supervisor`
  String get sup {
    return Intl.message('Supervisor', name: 'sup', desc: '', args: []);
  }

  /// `Service Office`
  String get serOffice {
    return Intl.message(
      'Service Office',
      name: 'serOffice',
      desc: '',
      args: [],
    );
  }

  /// `Buses`
  String get bus {
    return Intl.message('Buses', name: 'bus', desc: '', args: []);
  }

  /// `Other Feedback`
  String get otherFeed {
    return Intl.message(
      'Other Feedback',
      name: 'otherFeed',
      desc: '',
      args: [],
    );
  }

  /// `write your other feedback here`
  String get writeFeed {
    return Intl.message(
      'write your other feedback here',
      name: 'writeFeed',
      desc: '',
      args: [],
    );
  }

  /// `Send Feedback`
  String get sendFeed {
    return Intl.message('Send Feedback', name: 'sendFeed', desc: '', args: []);
  }

  /// `Support Ticket`
  String get supportTic {
    return Intl.message(
      'Support Ticket',
      name: 'supportTic',
      desc: '',
      args: [],
    );
  }

  /// `Select Department`
  String get selDep {
    return Intl.message(
      'Select Department',
      name: 'selDep',
      desc: '',
      args: [],
    );
  }

  /// `select issue department`
  String get selIssue {
    return Intl.message(
      'select issue department',
      name: 'selIssue',
      desc: '',
      args: [],
    );
  }

  /// `Request Details`
  String get reqDetails {
    return Intl.message(
      'Request Details',
      name: 'reqDetails',
      desc: '',
      args: [],
    );
  }

  /// `Select Luggage`
  String get selLig {
    return Intl.message('Select Luggage', name: 'selLig', desc: '', args: []);
  }

  /// `Submit Request`
  String get subReq {
    return Intl.message('Submit Request', name: 'subReq', desc: '', args: []);
  }

  /// `Bag Details:`
  String get bagDetails {
    return Intl.message('Bag Details:', name: 'bagDetails', desc: '', args: []);
  }

  /// `Scan QR Code`
  String get scanQr {
    return Intl.message('Scan QR Code', name: 'scanQr', desc: '', args: []);
  }

  /// `Scan QR Code for profile Information`
  String get code {
    return Intl.message(
      'Scan QR Code for profile Information',
      name: 'code',
      desc: '',
      args: [],
    );
  }

  /// `Requests`
  String get requests {
    return Intl.message('Requests', name: 'requests', desc: '', args: []);
  }

  /// `Resolved`
  String get resolved {
    return Intl.message('Resolved', name: 'resolved', desc: '', args: []);
  }

  /// `Request Sub Activity`
  String get reqSub {
    return Intl.message(
      'Request Sub Activity',
      name: 'reqSub',
      desc: '',
      args: [],
    );
  }

  /// `Under Process`
  String get under {
    return Intl.message('Under Process', name: 'under', desc: '', args: []);
  }

  /// `Pending`
  String get pending {
    return Intl.message('Pending', name: 'pending', desc: '', args: []);
  }

  /// `Open`
  String get op {
    return Intl.message('Open', name: 'op', desc: '', args: []);
  }

  /// `Add New Request`
  String get addReq {
    return Intl.message('Add New Request', name: 'addReq', desc: '', args: []);
  }

  /// `Status`
  String get status {
    return Intl.message('Status', name: 'status', desc: '', args: []);
  }

  /// `Lost & found`
  String get found {
    return Intl.message('Lost & found', name: 'found', desc: '', args: []);
  }

  /// `Request Details:`
  String get reqDetail {
    return Intl.message(
      'Request Details:',
      name: 'reqDetail',
      desc: '',
      args: [],
    );
  }

  /// `Supervisor Reply:`
  String get supReply {
    return Intl.message(
      'Supervisor Reply:',
      name: 'supReply',
      desc: '',
      args: [],
    );
  }

  /// `Enter flight number`
  String get fNo {
    return Intl.message('Enter flight number', name: 'fNo', desc: '', args: []);
  }

  /// `Activity Guide`
  String get acg {
    return Intl.message('Activity Guide', name: 'acg', desc: '', args: []);
  }

  /// `Main`
  String get main {
    return Intl.message('Main', name: 'main', desc: '', args: []);
  }

  /// `Email`
  String get email {
    return Intl.message('Email', name: 'email', desc: '', args: []);
  }

  /// `Activity`
  String get activity {
    return Intl.message('Activity', name: 'activity', desc: '', args: []);
  }

  /// `Select Activity`
  String get selectActivity {
    return Intl.message(
      'Select Activity',
      name: 'selectActivity',
      desc: '',
      args: [],
    );
  }

  /// `Select One of Activities`
  String get selectOneActivity {
    return Intl.message(
      'Select One of Activities',
      name: 'selectOneActivity',
      desc: '',
      args: [],
    );
  }

  /// `Order Number `
  String get orderNumber {
    return Intl.message(
      'Order Number ',
      name: 'orderNumber',
      desc: '',
      args: [],
    );
  }

  /// `The Cost`
  String get cost {
    return Intl.message('The Cost', name: 'cost', desc: '', args: []);
  }

  /// `Price`
  String get price {
    return Intl.message('Price', name: 'price', desc: '', args: []);
  }

  /// `Activity Phases`
  String get activityPhases {
    return Intl.message(
      'Activity Phases',
      name: 'activityPhases',
      desc: '',
      args: [],
    );
  }

  /// `Cash on Delivery`
  String get cashOnDelivery {
    return Intl.message(
      'Cash on Delivery',
      name: 'cashOnDelivery',
      desc: '',
      args: [],
    );
  }

  /// `Payment Method`
  String get paymentMethod {
    return Intl.message(
      'Payment Method',
      name: 'paymentMethod',
      desc: '',
      args: [],
    );
  }

  /// `Order Execution`
  String get orderExecution {
    return Intl.message(
      'Order Execution',
      name: 'orderExecution',
      desc: '',
      args: [],
    );
  }

  /// `Welcome to CementTech Store,your one-stop shop for all cement products with low price and high quality`
  String get splashScreenText {
    return Intl.message(
      'Welcome to CementTech Store,your one-stop shop for all cement products with low price and high quality',
      name: 'splashScreenText',
      desc: '',
      args: [],
    );
  }

  /// `requirements`
  String get requirements {
    return Intl.message(
      'requirements',
      name: 'requirements',
      desc: '',
      args: [],
    );
  }

  /// `ّInformation`
  String get information {
    return Intl.message(
      'ّInformation',
      name: 'information',
      desc: '',
      args: [],
    );
  }

  /// `history`
  String get history {
    return Intl.message('history', name: 'history', desc: '', args: []);
  }

  /// `Update`
  String get update {
    return Intl.message('Update', name: 'update', desc: '', args: []);
  }

  /// `Cancle The Order`
  String get cancleOrder {
    return Intl.message(
      'Cancle The Order',
      name: 'cancleOrder',
      desc: '',
      args: [],
    );
  }

  /// `Later`
  String get later {
    return Intl.message('Later', name: 'later', desc: '', args: []);
  }

  /// `Your account is registered successfully`
  String get registerSuccessfully {
    return Intl.message(
      'Your account is registered successfully',
      name: 'registerSuccessfully',
      desc: '',
      args: [],
    );
  }

  /// `Your request is sent successfully`
  String get requestSuccessfully {
    return Intl.message(
      'Your request is sent successfully',
      name: 'requestSuccessfully',
      desc: '',
      args: [],
    );
  }

  /// `Reservation`
  String get reservation {
    return Intl.message('Reservation', name: 'reservation', desc: '', args: []);
  }

  /// `Book Now`
  String get bookNow {
    return Intl.message('Book Now', name: 'bookNow', desc: '', args: []);
  }

  /// `Starting Search`
  String get startSearch {
    return Intl.message(
      'Starting Search',
      name: 'startSearch',
      desc: '',
      args: [],
    );
  }

  /// `Notifications`
  String get notifications {
    return Intl.message(
      'Notifications',
      name: 'notifications',
      desc: '',
      args: [],
    );
  }

  /// `Arrival Time`
  String get round {
    return Intl.message('Arrival Time', name: 'round', desc: '', args: []);
  }

  /// `Place Name`
  String get placeName {
    return Intl.message('Place Name', name: 'placeName', desc: '', args: []);
  }

  /// `Morning shift Number`
  String get morningShift {
    return Intl.message(
      'Morning shift Number',
      name: 'morningShift',
      desc: '',
      args: [],
    );
  }

  /// `evening shift Number`
  String get eveningShift {
    return Intl.message(
      'evening shift Number',
      name: 'eveningShift',
      desc: '',
      args: [],
    );
  }

  /// `Street Name`
  String get streetName {
    return Intl.message('Street Name', name: 'streetName', desc: '', args: []);
  }

  /// `Building Number`
  String get buildingNumber {
    return Intl.message(
      'Building Number',
      name: 'buildingNumber',
      desc: '',
      args: [],
    );
  }

  /// `Refund Reason`
  String get refundReason {
    return Intl.message(
      'Refund Reason',
      name: 'refundReason',
      desc: '',
      args: [],
    );
  }

  /// `First Name `
  String get firstName {
    return Intl.message('First Name ', name: 'firstName', desc: '', args: []);
  }

  /// `Last Name `
  String get lastName {
    return Intl.message('Last Name ', name: 'lastName', desc: '', args: []);
  }

  /// `Lowest Limit`
  String get limit {
    return Intl.message('Lowest Limit', name: 'limit', desc: '', args: []);
  }

  /// `are you sure that you want to cancle this order`
  String get cancleOrderBody {
    return Intl.message(
      'are you sure that you want to cancle this order',
      name: 'cancleOrderBody',
      desc: '',
      args: [],
    );
  }

  /// `Follow Order`
  String get followOeder {
    return Intl.message(
      'Follow Order',
      name: 'followOeder',
      desc: '',
      args: [],
    );
  }

  /// `Oreder Placed`
  String get orederPlaced {
    return Intl.message(
      'Oreder Placed',
      name: 'orederPlaced',
      desc: '',
      args: [],
    );
  }

  /// `Place Number`
  String get placeNumber {
    return Intl.message(
      'Place Number',
      name: 'placeNumber',
      desc: '',
      args: [],
    );
  }

  /// `Please Try Later`
  String get tryLater {
    return Intl.message(
      'Please Try Later',
      name: 'tryLater',
      desc: '',
      args: [],
    );
  }

  /// `there is no items in cart`
  String get noItemsInCart {
    return Intl.message(
      'there is no items in cart',
      name: 'noItemsInCart',
      desc: '',
      args: [],
    );
  }

  /// `Accepted`
  String get accepted {
    return Intl.message('Accepted', name: 'accepted', desc: '', args: []);
  }

  /// `Agent Code (Optional)`
  String get agentCode {
    return Intl.message(
      'Agent Code (Optional)',
      name: 'agentCode',
      desc: '',
      args: [],
    );
  }

  /// `there is an error`
  String get error {
    return Intl.message('there is an error', name: 'error', desc: '', args: []);
  }

  /// `refunds`
  String get refunds {
    return Intl.message('refunds', name: 'refunds', desc: '', args: []);
  }

  /// `packing`
  String get packing {
    return Intl.message('packing', name: 'packing', desc: '', args: []);
  }

  /// `companies`
  String get companies {
    return Intl.message('companies', name: 'companies', desc: '', args: []);
  }

  /// `Souhoola`
  String get souhoola {
    return Intl.message('Souhoola', name: 'souhoola', desc: '', args: []);
  }

  /// `Installments Services`
  String get installmentsServices {
    return Intl.message(
      'Installments Services',
      name: 'installmentsServices',
      desc: '',
      args: [],
    );
  }

  /// `Valu`
  String get valu {
    return Intl.message('Valu', name: 'valu', desc: '', args: []);
  }

  /// `if you doesnt receive a massage from us to continue buying use this qr code `
  String get mobilWalletOption {
    return Intl.message(
      'if you doesnt receive a massage from us to continue buying use this qr code ',
      name: 'mobilWalletOption',
      desc: '',
      args: [],
    );
  }

  /// `Installment with souhoola`
  String get installmentWithSouhoola {
    return Intl.message(
      'Installment with souhoola',
      name: 'installmentWithSouhoola',
      desc: '',
      args: [],
    );
  }

  /// `Installment with valu`
  String get installmentWithValu {
    return Intl.message(
      'Installment with valu',
      name: 'installmentWithValu',
      desc: '',
      args: [],
    );
  }

  /// `shipping`
  String get shipping {
    return Intl.message('shipping', name: 'shipping', desc: '', args: []);
  }

  /// `Delivered`
  String get delivered {
    return Intl.message('Delivered', name: 'delivered', desc: '', args: []);
  }

  /// `Details`
  String get details {
    return Intl.message('Details', name: 'details', desc: '', args: []);
  }

  /// `pay with fawry`
  String get fawry {
    return Intl.message('pay with fawry', name: 'fawry', desc: '', args: []);
  }

  /// `use to code to payment`
  String get fawryDes {
    return Intl.message(
      'use to code to payment',
      name: 'fawryDes',
      desc: '',
      args: [],
    );
  }

  /// ` Mobile Wallet`
  String get mobileWallet {
    return Intl.message(
      ' Mobile Wallet',
      name: 'mobileWallet',
      desc: '',
      args: [],
    );
  }

  /// `you can pay using your mobile phone wallet`
  String get mobileWalletDes {
    return Intl.message(
      'you can pay using your mobile phone wallet',
      name: 'mobileWalletDes',
      desc: '',
      args: [],
    );
  }

  /// `Follow Order`
  String get followOrder {
    return Intl.message(
      'Follow Order',
      name: 'followOrder',
      desc: '',
      args: [],
    );
  }

  /// `Back To Home`
  String get backToHome {
    return Intl.message('Back To Home', name: 'backToHome', desc: '', args: []);
  }

  /// `under delivery`
  String get underDelivery {
    return Intl.message(
      'under delivery',
      name: 'underDelivery',
      desc: '',
      args: [],
    );
  }

  /// `Get Payment Code`
  String get getPaymentCode {
    return Intl.message(
      'Get Payment Code',
      name: 'getPaymentCode',
      desc: '',
      args: [],
    );
  }

  /// `Your Code To Pay`
  String get fawryCode {
    return Intl.message(
      'Your Code To Pay',
      name: 'fawryCode',
      desc: '',
      args: [],
    );
  }

  /// `Other Types`
  String get otherTypes {
    return Intl.message('Other Types', name: 'otherTypes', desc: '', args: []);
  }

  /// `Re-Order`
  String get reOrder {
    return Intl.message('Re-Order', name: 'reOrder', desc: '', args: []);
  }

  /// `Order Details`
  String get orderDetails {
    return Intl.message(
      'Order Details',
      name: 'orderDetails',
      desc: '',
      args: [],
    );
  }

  /// `Oreder Placed and we review it `
  String get orederPlacedBody {
    return Intl.message(
      'Oreder Placed and we review it ',
      name: 'orederPlacedBody',
      desc: '',
      args: [],
    );
  }

  /// `Your order is Accepted`
  String get acceptedBody {
    return Intl.message(
      'Your order is Accepted',
      name: 'acceptedBody',
      desc: '',
      args: [],
    );
  }

  /// `we are packing your order and searching for nearest delivery`
  String get packingBody {
    return Intl.message(
      'we are packing your order and searching for nearest delivery',
      name: 'packingBody',
      desc: '',
      args: [],
    );
  }

  /// `please wait your order in the way `
  String get shippingBody {
    return Intl.message(
      'please wait your order in the way ',
      name: 'shippingBody',
      desc: '',
      args: [],
    );
  }

  /// `Your order is Delivered please call us for any problems`
  String get deliveredBody {
    return Intl.message(
      'Your order is Delivered please call us for any problems',
      name: 'deliveredBody',
      desc: '',
      args: [],
    );
  }

  /// `Categories`
  String get categories {
    return Intl.message('Categories', name: 'categories', desc: '', args: []);
  }

  /// `Category`
  String get category {
    return Intl.message('Category', name: 'category', desc: '', args: []);
  }

  /// `avaliable to loan`
  String get avaiableToLoan {
    return Intl.message(
      'avaliable to loan',
      name: 'avaiableToLoan',
      desc: '',
      args: [],
    );
  }

  /// `you loan`
  String get moneyYouLoan {
    return Intl.message('you loan', name: 'moneyYouLoan', desc: '', args: []);
  }

  /// `Best Offers`
  String get bestOffers {
    return Intl.message('Best Offers', name: 'bestOffers', desc: '', args: []);
  }

  /// `Enter The Mobile Wallet Number`
  String get enterMobileWallet {
    return Intl.message(
      'Enter The Mobile Wallet Number',
      name: 'enterMobileWallet',
      desc: '',
      args: [],
    );
  }

  /// `if you didnt receive massage scan this QRcode`
  String get qrCodeDes {
    return Intl.message(
      'if you didnt receive massage scan this QRcode',
      name: 'qrCodeDes',
      desc: '',
      args: [],
    );
  }

  /// `E-mail`
  String get eMail {
    return Intl.message('E-mail', name: 'eMail', desc: '', args: []);
  }

  /// `Current Requests`
  String get currentRequest {
    return Intl.message(
      'Current Requests',
      name: 'currentRequest',
      desc: '',
      args: [],
    );
  }

  /// `Previous Requests`
  String get previousRequest {
    return Intl.message(
      'Previous Requests',
      name: 'previousRequest',
      desc: '',
      args: [],
    );
  }

  /// `Update Available`
  String get updateAvailable {
    return Intl.message(
      'Update Available',
      name: 'updateAvailable',
      desc: '',
      args: [],
    );
  }

  /// `A new version of the TEAA is available. Please update to continue`
  String get updateBody {
    return Intl.message(
      'A new version of the TEAA is available. Please update to continue',
      name: 'updateBody',
      desc: '',
      args: [],
    );
  }

  /// `point`
  String get point {
    return Intl.message('point', name: 'point', desc: '', args: []);
  }

  /// `Remote Payment`
  String get remotePayment {
    return Intl.message(
      'Remote Payment',
      name: 'remotePayment',
      desc: '',
      args: [],
    );
  }

  /// `send to wallet `
  String get sendToWallet {
    return Intl.message(
      'send to wallet ',
      name: 'sendToWallet',
      desc: '',
      args: [],
    );
  }

  /// `Contact  With Client`
  String get contactWithClient {
    return Intl.message(
      'Contact  With Client',
      name: 'contactWithClient',
      desc: '',
      args: [],
    );
  }

  /// `you can use the wallet balance to buy `
  String get walletTitle {
    return Intl.message(
      'you can use the wallet balance to buy ',
      name: 'walletTitle',
      desc: '',
      args: [],
    );
  }

  /// `continue`
  String get continueShoping {
    return Intl.message(
      'continue',
      name: 'continueShoping',
      desc: '',
      args: [],
    );
  }

  /// `Please Select Address first`
  String get selectAddress {
    return Intl.message(
      'Please Select Address first',
      name: 'selectAddress',
      desc: '',
      args: [],
    );
  }

  /// `The Sale Time ends in`
  String get saleTime {
    return Intl.message(
      'The Sale Time ends in',
      name: 'saleTime',
      desc: '',
      args: [],
    );
  }

  /// `My Order`
  String get myOrder {
    return Intl.message('My Order', name: 'myOrder', desc: '', args: []);
  }

  /// `installments on 6 months`
  String get installments6mon {
    return Intl.message(
      'installments on 6 months',
      name: 'installments6mon',
      desc: '',
      args: [],
    );
  }

  /// `installments on 12 months`
  String get installments12mon {
    return Intl.message(
      'installments on 12 months',
      name: 'installments12mon',
      desc: '',
      args: [],
    );
  }

  /// `verified`
  String get verified {
    return Intl.message('verified', name: 'verified', desc: '', args: []);
  }

  /// `please verify your account`
  String get notVerified {
    return Intl.message(
      'please verify your account',
      name: 'notVerified',
      desc: '',
      args: [],
    );
  }

  /// `No results found`
  String get noResult {
    return Intl.message(
      'No results found',
      name: 'noResult',
      desc: '',
      args: [],
    );
  }

  /// `Exit App`
  String get exitApp {
    return Intl.message('Exit App', name: 'exitApp', desc: '', args: []);
  }

  /// `The amount`
  String get amount {
    return Intl.message('The amount', name: 'amount', desc: '', args: []);
  }

  /// `There is no data here till now`
  String get noData {
    return Intl.message(
      'There is no data here till now',
      name: 'noData',
      desc: '',
      args: [],
    );
  }

  /// `Are you sure you want to exit the app?`
  String get confirmExitMessage {
    return Intl.message(
      'Are you sure you want to exit the app?',
      name: 'confirmExitMessage',
      desc: '',
      args: [],
    );
  }

  /// `Recharge Wallet`
  String get rechangreWallet {
    return Intl.message(
      'Recharge Wallet',
      name: 'rechangreWallet',
      desc: '',
      args: [],
    );
  }

  /// `you can use any payment method`
  String get rechangreWalletBody {
    return Intl.message(
      'you can use any payment method',
      name: 'rechangreWalletBody',
      desc: '',
      args: [],
    );
  }

  /// `These credentials are not found`
  String get noUserFound {
    return Intl.message(
      'These credentials are not found',
      name: 'noUserFound',
      desc: '',
      args: [],
    );
  }

  /// `Best destinations`
  String get bestDestination {
    return Intl.message(
      'Best destinations',
      name: 'bestDestination',
      desc: '',
      args: [],
    );
  }

  /// `Our Offers`
  String get ourOffers {
    return Intl.message('Our Offers', name: 'ourOffers', desc: '', args: []);
  }

  /// `bookmarked`
  String get bookmarked {
    return Intl.message('bookmarked', name: 'bookmarked', desc: '', args: []);
  }

  /// `Our Tours`
  String get ourTours {
    return Intl.message('Our Tours', name: 'ourTours', desc: '', args: []);
  }

  /// `Previous Trips`
  String get previousTrips {
    return Intl.message(
      'Previous Trips',
      name: 'previousTrips',
      desc: '',
      args: [],
    );
  }

  /// `Postal Code`
  String get postalCode {
    return Intl.message('Postal Code', name: 'postalCode', desc: '', args: []);
  }

  /// `write more details like : Type Of The Service`
  String get worktype {
    return Intl.message(
      'write more details like : Type Of The Service',
      name: 'worktype',
      desc: '',
      args: [],
    );
  }

  /// `You can add more than one address for more than branch`
  String get addressListTitle {
    return Intl.message(
      'You can add more than one address for more than branch',
      name: 'addressListTitle',
      desc: '',
      args: [],
    );
  }

  /// `set your organization name`
  String get workName {
    return Intl.message(
      'set your organization name',
      name: 'workName',
      desc: '',
      args: [],
    );
  }

  /// `choose your city`
  String get selectCity {
    return Intl.message(
      'choose your city',
      name: 'selectCity',
      desc: '',
      args: [],
    );
  }

  /// `please dont let this filed null`
  String get pleaseEndterValue {
    return Intl.message(
      'please dont let this filed null',
      name: 'pleaseEndterValue',
      desc: '',
      args: [],
    );
  }

  /// `Hours`
  String get hours {
    return Intl.message('Hours', name: 'hours', desc: '', args: []);
  }

  /// `Version`
  String get version {
    return Intl.message('Version', name: 'version', desc: '', args: []);
  }

  /// `Don’t have an account?`
  String get dontHaveAccont {
    return Intl.message(
      'Don’t have an account?',
      name: 'dontHaveAccont',
      desc: '',
      args: [],
    );
  }

  /// `No Previous Tours till now `
  String get noPreviousTrips {
    return Intl.message(
      'No Previous Tours till now ',
      name: 'noPreviousTrips',
      desc: '',
      args: [],
    );
  }

  /// `Thank you for using our app!`
  String get thanksMassage {
    return Intl.message(
      'Thank you for using our app!',
      name: 'thanksMassage',
      desc: '',
      args: [],
    );
  }

  /// `Search`
  String get searchTitle {
    return Intl.message('Search', name: 'searchTitle', desc: '', args: []);
  }

  /// `search...`
  String get searchPlaceholder {
    return Intl.message(
      'search...',
      name: 'searchPlaceholder',
      desc: '',
      args: [],
    );
  }

  /// `Help`
  String get helper {
    return Intl.message('Help', name: 'helper', desc: '', args: []);
  }

  /// `explore Iraq  `
  String get exploreIraq {
    return Intl.message(
      'explore Iraq  ',
      name: 'exploreIraq',
      desc: '',
      args: [],
    );
  }

  /// `Visa Form data`
  String get visaForm {
    return Intl.message('Visa Form data', name: 'visaForm', desc: '', args: []);
  }

  /// `Visa Details`
  String get visaDetails {
    return Intl.message(
      'Visa Details',
      name: 'visaDetails',
      desc: '',
      args: [],
    );
  }

  /// `Tour Details`
  String get tourDetails {
    return Intl.message(
      'Tour Details',
      name: 'tourDetails',
      desc: '',
      args: [],
    );
  }

  /// `Tour Form data`
  String get tourForm {
    return Intl.message('Tour Form data', name: 'tourForm', desc: '', args: []);
  }

  /// ` Additional Notes`
  String get notes {
    return Intl.message(' Additional Notes', name: 'notes', desc: '', args: []);
  }

  /// `Stagnant Items.......You can upload your stagnant items to your account and they will be rotated and resold on your behalf`
  String get stagnantItems {
    return Intl.message(
      'Stagnant Items.......You can upload your stagnant items to your account and they will be rotated and resold on your behalf',
      name: 'stagnantItems',
      desc: '',
      args: [],
    );
  }

  /// `Wallet......Here you can get money and cashback to help you increase your profits`
  String get wallet {
    return Intl.message(
      'Wallet......Here you can get money and cashback to help you increase your profits',
      name: 'wallet',
      desc: '',
      args: [],
    );
  }

  /// `Customer's Stagnant Items.....You can browse stagnant items at other pharmacies in your area and repurchase them`
  String get customerStagnantItems {
    return Intl.message(
      'Customer\'s Stagnant Items.....You can browse stagnant items at other pharmacies in your area and repurchase them',
      name: 'customerStagnantItems',
      desc: '',
      args: [],
    );
  }

  /// `Missing Items Notebook....You can upload a missing items sheet or an Excel sheet, and the orders will be processed automatically on your behalf`
  String get missingItemsNotebook {
    return Intl.message(
      'Missing Items Notebook....You can upload a missing items sheet or an Excel sheet, and the orders will be processed automatically on your behalf',
      name: 'missingItemsNotebook',
      desc: '',
      args: [],
    );
  }

  /// `Expired Products or Expired Items.......Dispose of expired items by uploading expired or damaged products`
  String get expiredProducts {
    return Intl.message(
      'Expired Products or Expired Items.......Dispose of expired items by uploading expired or damaged products',
      name: 'expiredProducts',
      desc: '',
      args: [],
    );
  }

  /// `Return Order....You can return an item within 3 days after receiving it`
  String get returnOrder {
    return Intl.message(
      'Return Order....You can return an item within 3 days after receiving it',
      name: 'returnOrder',
      desc: '',
      args: [],
    );
  }

  /// `My Favorite Items.....What you want to buy later`
  String get favoriteItems {
    return Intl.message(
      'My Favorite Items.....What you want to buy later',
      name: 'favoriteItems',
      desc: '',
      args: [],
    );
  }

  /// `Add Address`
  String get addNewAddress {
    return Intl.message(
      'Add Address',
      name: 'addNewAddress',
      desc: '',
      args: [],
    );
  }

  /// `More`
  String get more {
    return Intl.message('More', name: 'more', desc: '', args: []);
  }

  /// `i am here to help you around the whole app`
  String get onbordingMsg {
    return Intl.message(
      'i am here to help you around the whole app',
      name: 'onbordingMsg',
      desc: '',
      args: [],
    );
  }

  /// `Log Out`
  String get logOut {
    return Intl.message('Log Out', name: 'logOut', desc: '', args: []);
  }

  /// `en`
  String get localeee {
    return Intl.message('en', name: 'localeee', desc: '', args: []);
  }

  /// `Confirm Address`
  String get confirmAddress {
    return Intl.message(
      'Confirm Address',
      name: 'confirmAddress',
      desc: '',
      args: [],
    );
  }

  /// `Payment Method`
  String get choosePaymentMethod {
    return Intl.message(
      'Payment Method',
      name: 'choosePaymentMethod',
      desc: '',
      args: [],
    );
  }

  /// `You will pay when you receive the order`
  String get cashOnDeliverydes {
    return Intl.message(
      'You will pay when you receive the order',
      name: 'cashOnDeliverydes',
      desc: '',
      args: [],
    );
  }

  /// `pay from your wallet balance`
  String get walletCacheDes {
    return Intl.message(
      'pay from your wallet balance',
      name: 'walletCacheDes',
      desc: '',
      args: [],
    );
  }

  /// `pay using your credit card `
  String get creditCarddes {
    return Intl.message(
      'pay using your credit card ',
      name: 'creditCarddes',
      desc: '',
      args: [],
    );
  }

  /// `buy now and buy later `
  String get delayedCashDes {
    return Intl.message(
      'buy now and buy later ',
      name: 'delayedCashDes',
      desc: '',
      args: [],
    );
  }

  /// `Credit Card`
  String get creditCard {
    return Intl.message('Credit Card', name: 'creditCard', desc: '', args: []);
  }

  /// `Delayed Cash`
  String get delayedCash {
    return Intl.message(
      'Delayed Cash',
      name: 'delayedCash',
      desc: '',
      args: [],
    );
  }

  /// `Register`
  String get register {
    return Intl.message('Register', name: 'register', desc: '', args: []);
  }

  /// `Purchases / Warehouses`
  String get repositories {
    return Intl.message(
      'Purchases / Warehouses',
      name: 'repositories',
      desc: '',
      args: [],
    );
  }

  /// `Human Resources`
  String get humanResources {
    return Intl.message(
      'Human Resources',
      name: 'humanResources',
      desc: '',
      args: [],
    );
  }

  /// `Salaries and Bonuses`
  String get salaries {
    return Intl.message(
      'Salaries and Bonuses',
      name: 'salaries',
      desc: '',
      args: [],
    );
  }

  /// `Reservation Management`
  String get registration {
    return Intl.message(
      'Reservation Management',
      name: 'registration',
      desc: '',
      args: [],
    );
  }

  /// `Training and Education`
  String get study {
    return Intl.message(
      'Training and Education',
      name: 'study',
      desc: '',
      args: [],
    );
  }

  /// `Most Sold Items`
  String get mostSoldItmes {
    return Intl.message(
      'Most Sold Items',
      name: 'mostSoldItmes',
      desc: '',
      args: [],
    );
  }

  /// `Welcome in C-Store for cement products`
  String get welcomeMessage {
    return Intl.message(
      'Welcome in C-Store for cement products',
      name: 'welcomeMessage',
      desc: '',
      args: [],
    );
  }

  /// `we have many awesome prizes like Laptops , iphone and cars`
  String get welcomeDescreption {
    return Intl.message(
      'we have many awesome prizes like Laptops , iphone and cars',
      name: 'welcomeDescreption',
      desc: '',
      args: [],
    );
  }

  /// `Cash on Delivery Screen`
  String get cashOnDeliveryScreen {
    return Intl.message(
      'Cash on Delivery Screen',
      name: 'cashOnDeliveryScreen',
      desc: '',
      args: [],
    );
  }

  /// `Credit Card Payment Screen`
  String get creditCardPaymentScreen {
    return Intl.message(
      'Credit Card Payment Screen',
      name: 'creditCardPaymentScreen',
      desc: '',
      args: [],
    );
  }

  /// `Delayed Cash Payment Screen`
  String get delayedCashScreen {
    return Intl.message(
      'Delayed Cash Payment Screen',
      name: 'delayedCashScreen',
      desc: '',
      args: [],
    );
  }

  /// `You have 30 days to repay the debt`
  String get delayedCashScreenDesTime {
    return Intl.message(
      'You have 30 days to repay the debt',
      name: 'delayedCashScreenDesTime',
      desc: '',
      args: [],
    );
  }

  /// `Change the language of the application`
  String get languageDes {
    return Intl.message(
      'Change the language of the application',
      name: 'languageDes',
      desc: '',
      args: [],
    );
  }

  /// `Make Changes to your profile Info`
  String get profileInfoDes {
    return Intl.message(
      'Make Changes to your profile Info',
      name: 'profileInfoDes',
      desc: '',
      args: [],
    );
  }

  /// ` you can take a look on our newest digital services `
  String get medicalServices {
    return Intl.message(
      ' you can take a look on our newest digital services ',
      name: 'medicalServices',
      desc: '',
      args: [],
    );
  }

  /// `you can speak to a specialist`
  String get speakToUs {
    return Intl.message(
      'you can speak to a specialist',
      name: 'speakToUs',
      desc: '',
      args: [],
    );
  }

  /// `Language`
  String get language {
    return Intl.message('Language', name: 'language', desc: '', args: []);
  }

  /// `Profile Info`
  String get profileInfo {
    return Intl.message(
      'Profile Info',
      name: 'profileInfo',
      desc: '',
      args: [],
    );
  }

  /// `conect with us for varification `
  String get verificationStatus {
    return Intl.message(
      'conect with us for varification ',
      name: 'verificationStatus',
      desc: '',
      args: [],
    );
  }

  /// `Select Quantitiy at First`
  String get selectQuantitiy {
    return Intl.message(
      'Select Quantitiy at First',
      name: 'selectQuantitiy',
      desc: '',
      args: [],
    );
  }

  /// `Pending`
  String get refused {
    return Intl.message('Pending', name: 'refused', desc: '', args: []);
  }

  /// ` please enter valid email `
  String get invalidEmail {
    return Intl.message(
      ' please enter valid email ',
      name: 'invalidEmail',
      desc: '',
      args: [],
    );
  }

  /// `Compare Price`
  String get comparePrice {
    return Intl.message(
      'Compare Price',
      name: 'comparePrice',
      desc: '',
      args: [],
    );
  }

  /// `discont`
  String get discont {
    return Intl.message('discont', name: 'discont', desc: '', args: []);
  }

  /// `There is no internet connection`
  String get noInternetConnection {
    return Intl.message(
      'There is no internet connection',
      name: 'noInternetConnection',
      desc: '',
      args: [],
    );
  }

  /// `Classic Mushaf`
  String get themeClassicMushaf {
    return Intl.message(
      'Classic Mushaf',
      name: 'themeClassicMushaf',
      desc: '',
      args: [],
    );
  }

  /// `Madinah Green`
  String get themeMadinahGreen {
    return Intl.message(
      'Madinah Green',
      name: 'themeMadinahGreen',
      desc: '',
      args: [],
    );
  }

  /// `Night Mode`
  String get themeNightMode {
    return Intl.message(
      'Night Mode',
      name: 'themeNightMode',
      desc: '',
      args: [],
    );
  }

  /// `Islamic Gold`
  String get themeIslamicGold {
    return Intl.message(
      'Islamic Gold',
      name: 'themeIslamicGold',
      desc: '',
      args: [],
    );
  }

  /// `Calm Blue`
  String get themeCalmBlue {
    return Intl.message('Calm Blue', name: 'themeCalmBlue', desc: '', args: []);
  }

  /// `Vintage Sepia`
  String get themeVintageSepia {
    return Intl.message(
      'Vintage Sepia',
      name: 'themeVintageSepia',
      desc: '',
      args: [],
    );
  }

  /// `Natural Olive`
  String get themeNaturalOlive {
    return Intl.message(
      'Natural Olive',
      name: 'themeNaturalOlive',
      desc: '',
      args: [],
    );
  }

  /// `Soft Rose`
  String get themeSoftRose {
    return Intl.message('Soft Rose', name: 'themeSoftRose', desc: '', args: []);
  }

  /// `Select Reading Theme`
  String get selectReadingTheme {
    return Intl.message(
      'Select Reading Theme',
      name: 'selectReadingTheme',
      desc: '',
      args: [],
    );
  }

  /// `Change Color`
  String get changeColor {
    return Intl.message(
      'Change Color',
      name: 'changeColor',
      desc: '',
      args: [],
    );
  }

  /// `New Product`
  String get newProduct {
    return Intl.message('New Product', name: 'newProduct', desc: '', args: []);
  }

  /// `Select region`
  String get selectRegion {
    return Intl.message(
      'Select region',
      name: 'selectRegion',
      desc: '',
      args: [],
    );
  }

  /// `Verification Code`
  String get verificationCode {
    return Intl.message(
      'Verification Code',
      name: 'verificationCode',
      desc: '',
      args: [],
    );
  }

  /// `The Reset Code is Sent`
  String get emailPasswordSend {
    return Intl.message(
      'The Reset Code is Sent',
      name: 'emailPasswordSend',
      desc: '',
      args: [],
    );
  }

  /// `enter your email or phonenumber and we will send you a password resent link.`
  String get forgetPasswordBody {
    return Intl.message(
      'enter your email or phonenumber and we will send you a password resent link.',
      name: 'forgetPasswordBody',
      desc: '',
      args: [],
    );
  }

  /// `Minutes`
  String get minutes {
    return Intl.message('Minutes', name: 'minutes', desc: '', args: []);
  }

  /// `Cash Back`
  String get cashBack {
    return Intl.message('Cash Back', name: 'cashBack', desc: '', args: []);
  }

  /// `you have : `
  String get youHave {
    return Intl.message('you have : ', name: 'youHave', desc: '', args: []);
  }

  /// `The password cant be less than 4 `
  String get passwordTooShort {
    return Intl.message(
      'The password cant be less than 4 ',
      name: 'passwordTooShort',
      desc: '',
      args: [],
    );
  }

  /// `You Can Convert Your Points To :  `
  String get contvertTo {
    return Intl.message(
      'You Can Convert Your Points To :  ',
      name: 'contvertTo',
      desc: '',
      args: [],
    );
  }

  /// `Seconds`
  String get seconds {
    return Intl.message('Seconds', name: 'seconds', desc: '', args: []);
  }

  /// `Confirm Payment`
  String get confirmPayment {
    return Intl.message(
      'Confirm Payment',
      name: 'confirmPayment',
      desc: '',
      args: [],
    );
  }

  /// `Continue Payment`
  String get continuePayment {
    return Intl.message(
      'Continue Payment',
      name: 'continuePayment',
      desc: '',
      args: [],
    );
  }

  /// `Also Available In :`
  String get alsoAvailable {
    return Intl.message(
      'Also Available In :',
      name: 'alsoAvailable',
      desc: '',
      args: [],
    );
  }

  /// `Re Change`
  String get reCharge {
    return Intl.message('Re Change', name: 'reCharge', desc: '', args: []);
  }

  /// `Select Language`
  String get selectLanguage {
    return Intl.message(
      'Select Language',
      name: 'selectLanguage',
      desc: '',
      args: [],
    );
  }

  /// `Select Country`
  String get selectCountry {
    return Intl.message(
      'Select Country',
      name: 'selectCountry',
      desc: '',
      args: [],
    );
  }

  /// `You cannot buy for less than 500 pounds`
  String get buyingLimit {
    return Intl.message(
      'You cannot buy for less than 500 pounds',
      name: 'buyingLimit',
      desc: '',
      args: [],
    );
  }

  /// `saudiArabia`
  String get saudiArabia {
    return Intl.message('saudiArabia', name: 'saudiArabia', desc: '', args: []);
  }

  /// `Sign In`
  String get singIn {
    return Intl.message('Sign In', name: 'singIn', desc: '', args: []);
  }

  /// `Size`
  String get size {
    return Intl.message('Size', name: 'size', desc: '', args: []);
  }

  /// `Description`
  String get description {
    return Intl.message('Description', name: 'description', desc: '', args: []);
  }

  /// `Today Sale`
  String get flashTodaySale {
    return Intl.message(
      'Today Sale',
      name: 'flashTodaySale',
      desc: '',
      args: [],
    );
  }

  /// `Color`
  String get color {
    return Intl.message('Color', name: 'color', desc: '', args: []);
  }

  /// `EGP`
  String get pound {
    return Intl.message('EGP', name: 'pound', desc: '', args: []);
  }

  /// `Shipping Address`
  String get address {
    return Intl.message(
      'Shipping Address',
      name: 'address',
      desc: '',
      args: [],
    );
  }

  /// `Welcome  `
  String get loginTitle {
    return Intl.message('Welcome  ', name: 'loginTitle', desc: '', args: []);
  }

  /// `already you have an account ?`
  String get alreadyYouHaveAccount {
    return Intl.message(
      'already you have an account ?',
      name: 'alreadyYouHaveAccount',
      desc: '',
      args: [],
    );
  }

  /// `The World Is Waiting – Let’s Go!`
  String get loginBody {
    return Intl.message(
      'The World Is Waiting – Let’s Go!',
      name: 'loginBody',
      desc: '',
      args: [],
    );
  }

  /// `Buy Now`
  String get buyNow {
    return Intl.message('Buy Now', name: 'buyNow', desc: '', args: []);
  }

  /// `Sing Up`
  String get singUp {
    return Intl.message('Sing Up', name: 'singUp', desc: '', args: []);
  }

  /// `Chat US`
  String get chatUs {
    return Intl.message('Chat US', name: 'chatUs', desc: '', args: []);
  }

  /// `New `
  String get createAccount {
    return Intl.message('New ', name: 'createAccount', desc: '', args: []);
  }

  /// `Address Details`
  String get addressDetails {
    return Intl.message(
      'Address Details',
      name: 'addressDetails',
      desc: '',
      args: [],
    );
  }

  /// `Last Address`
  String get lastAddress {
    return Intl.message(
      'Last Address',
      name: 'lastAddress',
      desc: '',
      args: [],
    );
  }

  /// `Pay with Last Address`
  String get payWithLastAddress {
    return Intl.message(
      'Pay with Last Address',
      name: 'payWithLastAddress',
      desc: '',
      args: [],
    );
  }

  /// `Save and Pay`
  String get saveAndPay {
    return Intl.message('Save and Pay', name: 'saveAndPay', desc: '', args: []);
  }

  /// `Phone Number`
  String get phoneNumber {
    return Intl.message(
      'Phone Number',
      name: 'phoneNumber',
      desc: '',
      args: [],
    );
  }

  /// `Forget Password ?`
  String get forgetPassword {
    return Intl.message(
      'Forget Password ?',
      name: 'forgetPassword',
      desc: '',
      args: [],
    );
  }

  /// `Remember Me`
  String get rememberMe {
    return Intl.message('Remember Me', name: 'rememberMe', desc: '', args: []);
  }

  /// `Reviews`
  String get reviews {
    return Intl.message('Reviews', name: 'reviews', desc: '', args: []);
  }

  /// `Total`
  String get total {
    return Intl.message('Total', name: 'total', desc: '', args: []);
  }

  /// `Check Out`
  String get checkOut {
    return Intl.message('Check Out', name: 'checkOut', desc: '', args: []);
  }

  /// `Check In`
  String get checkIn {
    return Intl.message('Check In', name: 'checkIn', desc: '', args: []);
  }

  /// `checked In`
  String get checkedIn {
    return Intl.message('checked In', name: 'checkedIn', desc: '', args: []);
  }

  /// `checked Out`
  String get checkedOut {
    return Intl.message('checked Out', name: 'checkedOut', desc: '', args: []);
  }

  /// `not checked In`
  String get notCheckedIn {
    return Intl.message(
      'not checked In',
      name: 'notCheckedIn',
      desc: '',
      args: [],
    );
  }

  /// `not checked Out`
  String get notCheckedOut {
    return Intl.message(
      'not checked Out',
      name: 'notCheckedOut',
      desc: '',
      args: [],
    );
  }

  /// `Points`
  String get points {
    return Intl.message('Points', name: 'points', desc: '', args: []);
  }

  /// `Not Available`
  String get notAvailable {
    return Intl.message(
      'Not Available',
      name: 'notAvailable',
      desc: '',
      args: [],
    );
  }

  /// `Available`
  String get available {
    return Intl.message('Available', name: 'available', desc: '', args: []);
  }

  /// `Edit The Product`
  String get editProduct {
    return Intl.message(
      'Edit The Product',
      name: 'editProduct',
      desc: '',
      args: [],
    );
  }

  /// `Add To Cart`
  String get addToCart {
    return Intl.message('Add To Cart', name: 'addToCart', desc: '', args: []);
  }

  /// `Settings`
  String get settings {
    return Intl.message('Settings', name: 'settings', desc: '', args: []);
  }

  /// `Attendance Statistics`
  String get attendanceStatistics {
    return Intl.message(
      'Attendance Statistics',
      name: 'attendanceStatistics',
      desc: '',
      args: [],
    );
  }

  /// `Total Late Hours`
  String get totalLateHours {
    return Intl.message(
      'Total Late Hours',
      name: 'totalLateHours',
      desc: '',
      args: [],
    );
  }

  /// `Total Work Hours`
  String get totalWorkHours {
    return Intl.message(
      'Total Work Hours',
      name: 'totalWorkHours',
      desc: '',
      args: [],
    );
  }

  /// `Total Early Departure Hours`
  String get totalEarlyDepartureHours {
    return Intl.message(
      'Total Early Departure Hours',
      name: 'totalEarlyDepartureHours',
      desc: '',
      args: [],
    );
  }

  /// `Total Overtime Hours`
  String get totalOvertimeHours {
    return Intl.message(
      'Total Overtime Hours',
      name: 'totalOvertimeHours',
      desc: '',
      args: [],
    );
  }

  /// `hour`
  String get hour {
    return Intl.message('hour', name: 'hour', desc: '', args: []);
  }

  /// `Announcement`
  String get announcement {
    return Intl.message(
      'Announcement',
      name: 'announcement',
      desc: '',
      args: [],
    );
  }

  /// `Reminder: Please record your attendance and leaving regularly. The deadline for submitting leave requests is next Sunday.`
  String get attendanceAnnouncementBody {
    return Intl.message(
      'Reminder: Please record your attendance and leaving regularly. The deadline for submitting leave requests is next Sunday.',
      name: 'attendanceAnnouncementBody',
      desc: '',
      args: [],
    );
  }

  /// `Save`
  String get saveChanges {
    return Intl.message('Save', name: 'saveChanges', desc: '', args: []);
  }

  /// `Company`
  String get company {
    return Intl.message('Company', name: 'company', desc: '', args: []);
  }

  /// `Attendance Type`
  String get attendanceType {
    return Intl.message(
      'Attendance Type',
      name: 'attendanceType',
      desc: '',
      args: [],
    );
  }

  /// `Forget Reason`
  String get forgetReason {
    return Intl.message(
      'Forget Reason',
      name: 'forgetReason',
      desc: '',
      args: [],
    );
  }

  /// `Car Brand`
  String get carBrand {
    return Intl.message('Car Brand', name: 'carBrand', desc: '', args: []);
  }

  /// `Select Car Brand`
  String get selectCarBrand {
    return Intl.message(
      'Select Car Brand',
      name: 'selectCarBrand',
      desc: '',
      args: [],
    );
  }

  /// `Car Color`
  String get carColor {
    return Intl.message('Car Color', name: 'carColor', desc: '', args: []);
  }

  /// `Select Car Color`
  String get selectCarColor {
    return Intl.message(
      'Select Car Color',
      name: 'selectCarColor',
      desc: '',
      args: [],
    );
  }

  /// `Car Number`
  String get carNumber {
    return Intl.message('Car Number', name: 'carNumber', desc: '', args: []);
  }

  /// `e.g. ABC 1234`
  String get carNumberHint {
    return Intl.message(
      'e.g. ABC 1234',
      name: 'carNumberHint',
      desc: '',
      args: [],
    );
  }

  /// `Car Type (Brand)`
  String get carType {
    return Intl.message(
      'Car Type (Brand)',
      name: 'carType',
      desc: '',
      args: [],
    );
  }

  /// `Plate Number`
  String get plateNumber {
    return Intl.message(
      'Plate Number',
      name: 'plateNumber',
      desc: '',
      args: [],
    );
  }

  /// `Status`
  String get requestState {
    return Intl.message('Status', name: 'requestState', desc: '', args: []);
  }

  /// `Check-In Time (from record)`
  String get checkInFromRecord {
    return Intl.message(
      'Check-In Time (from record)',
      name: 'checkInFromRecord',
      desc: '',
      args: [],
    );
  }

  /// `Check-Out Time (from record)`
  String get checkOutFromRecord {
    return Intl.message(
      'Check-Out Time (from record)',
      name: 'checkOutFromRecord',
      desc: '',
      args: [],
    );
  }

  /// `Check-In Mode`
  String get checkInMode {
    return Intl.message(
      'Check-In Mode',
      name: 'checkInMode',
      desc: '',
      args: [],
    );
  }

  /// `Check-Out Mode`
  String get checkOutMode {
    return Intl.message(
      'Check-Out Mode',
      name: 'checkOutMode',
      desc: '',
      args: [],
    );
  }

  /// `Fingerprint Record`
  String get fingerprintRecord {
    return Intl.message(
      'Fingerprint Record',
      name: 'fingerprintRecord',
      desc: '',
      args: [],
    );
  }

  /// `Hours Worked`
  String get hoursWorked {
    return Intl.message(
      'Hours Worked',
      name: 'hoursWorked',
      desc: '',
      args: [],
    );
  }

  /// `Overtime`
  String get overtime {
    return Intl.message('Overtime', name: 'overtime', desc: '', args: []);
  }

  /// `Time`
  String get timeOfDay {
    return Intl.message('Time', name: 'timeOfDay', desc: '', args: []);
  }

  /// `Attendance History`
  String get attendanceHistories {
    return Intl.message(
      'Attendance History',
      name: 'attendanceHistories',
      desc: '',
      args: [],
    );
  }

  /// `Loading...`
  String get loading {
    return Intl.message('Loading...', name: 'loading', desc: '', args: []);
  }

  /// `select time`
  String get selectTime {
    return Intl.message('select time', name: 'selectTime', desc: '', args: []);
  }

  /// `Confirm password`
  String get confirmPassword {
    return Intl.message(
      'Confirm password',
      name: 'confirmPassword',
      desc: '',
      args: [],
    );
  }

  /// `Unknown`
  String get unknown {
    return Intl.message('Unknown', name: 'unknown', desc: '', args: []);
  }

  /// `Continue`
  String get coontinue {
    return Intl.message('Continue', name: 'coontinue', desc: '', args: []);
  }

  /// `User Name`
  String get userName {
    return Intl.message('User Name', name: 'userName', desc: '', args: []);
  }

  /// `Welcome Again`
  String get letsStart {
    return Intl.message('Welcome Again', name: 'letsStart', desc: '', args: []);
  }

  /// ` WhatsApp`
  String get whatsApp {
    return Intl.message(' WhatsApp', name: 'whatsApp', desc: '', args: []);
  }

  /// `What are the latest offers ?`
  String get whatisNewOffers {
    return Intl.message(
      'What are the latest offers ?',
      name: 'whatisNewOffers',
      desc: '',
      args: [],
    );
  }

  /// `Terms of use`
  String get termsOfUse {
    return Intl.message('Terms of use', name: 'termsOfUse', desc: '', args: []);
  }

  /// `I agree to `
  String get iAgreeTo {
    return Intl.message('I agree to ', name: 'iAgreeTo', desc: '', args: []);
  }

  /// `see all`
  String get seeAll {
    return Intl.message('see all', name: 'seeAll', desc: '', args: []);
  }

  /// `View all`
  String get viewAll {
    return Intl.message('View all', name: 'viewAll', desc: '', args: []);
  }

  /// `Visa`
  String get visa {
    return Intl.message('Visa', name: 'visa', desc: '', args: []);
  }

  /// `Tours`
  String get tours {
    return Intl.message('Tours', name: 'tours', desc: '', args: []);
  }

  /// `Best Destinations`
  String get bestDestinations {
    return Intl.message(
      'Best Destinations',
      name: 'bestDestinations',
      desc: '',
      args: [],
    );
  }

  /// `Offers`
  String get offers {
    return Intl.message('Offers', name: 'offers', desc: '', args: []);
  }

  /// `My Wallet`
  String get myWallet {
    return Intl.message('My Wallet', name: 'myWallet', desc: '', args: []);
  }

  /// `Favorites`
  String get favorite {
    return Intl.message('Favorites', name: 'favorite', desc: '', args: []);
  }

  /// `Choose Image`
  String get chooseImage {
    return Intl.message(
      'Choose Image',
      name: 'chooseImage',
      desc: '',
      args: [],
    );
  }

  /// `Unit Price`
  String get unitPrice {
    return Intl.message('Unit Price', name: 'unitPrice', desc: '', args: []);
  }

  /// `Quantity`
  String get quantity {
    return Intl.message('Quantity', name: 'quantity', desc: '', args: []);
  }

  /// `Price before Offer`
  String get priceBeforeOffer {
    return Intl.message(
      'Price before Offer',
      name: 'priceBeforeOffer',
      desc: '',
      args: [],
    );
  }

  /// `Price After Offer`
  String get priceAfterOffer {
    return Intl.message(
      'Price After Offer',
      name: 'priceAfterOffer',
      desc: '',
      args: [],
    );
  }

  /// `avaliable Itmes`
  String get avaliableItemCount {
    return Intl.message(
      'avaliable Itmes',
      name: 'avaliableItemCount',
      desc: '',
      args: [],
    );
  }

  /// `My Stagnant Categories`
  String get myStagnantCategories {
    return Intl.message(
      'My Stagnant Categories',
      name: 'myStagnantCategories',
      desc: '',
      args: [],
    );
  }

  /// `Pharmacy`
  String get pharmacy {
    return Intl.message('Pharmacy', name: 'pharmacy', desc: '', args: []);
  }

  /// `Product Name`
  String get productName {
    return Intl.message(
      'Product Name',
      name: 'productName',
      desc: '',
      args: [],
    );
  }

  /// `Product Description`
  String get productDescription {
    return Intl.message(
      'Product Description',
      name: 'productDescription',
      desc: '',
      args: [],
    );
  }

  /// `Call us`
  String get callUs {
    return Intl.message('Call us', name: 'callUs', desc: '', args: []);
  }

  /// `Medicine`
  String get medicine {
    return Intl.message('Medicine', name: 'medicine', desc: '', args: []);
  }

  /// `Medical Supplies`
  String get medicalSupplies {
    return Intl.message(
      'Medical Supplies',
      name: 'medicalSupplies',
      desc: '',
      args: [],
    );
  }

  /// `Stagnant categories`
  String get stagnantCategories {
    return Intl.message(
      'Stagnant categories',
      name: 'stagnantCategories',
      desc: '',
      args: [],
    );
  }

  /// `Cart`
  String get cart {
    return Intl.message('Cart', name: 'cart', desc: '', args: []);
  }

  /// `as`
  String get as {
    return Intl.message('as', name: 'as', desc: '', args: []);
  }

  /// `To access the AI Helper feature please log in or create an account`
  String get toAccessAiHelperLog {
    return Intl.message(
      'To access the AI Helper feature please log in or create an account',
      name: 'toAccessAiHelperLog',
      desc: '',
      args: [],
    );
  }

  /// `Gallery`
  String get gallery {
    return Intl.message('Gallery', name: 'gallery', desc: '', args: []);
  }

  /// `Camera`
  String get camera {
    return Intl.message('Camera', name: 'camera', desc: '', args: []);
  }

  /// `Try searching with different keywords or browse all questions`
  String get trySearch {
    return Intl.message(
      'Try searching with different keywords or browse all questions',
      name: 'trySearch',
      desc: '',
      args: [],
    );
  }

  /// `Show All Questions`
  String get showAllQuestions {
    return Intl.message(
      'Show All Questions',
      name: 'showAllQuestions',
      desc: '',
      args: [],
    );
  }

  /// `No results found`
  String get noResults {
    return Intl.message(
      'No results found',
      name: 'noResults',
      desc: '',
      args: [],
    );
  }

  /// `Search in questions`
  String get SearchInQuestions {
    return Intl.message(
      'Search in questions',
      name: 'SearchInQuestions',
      desc: '',
      args: [],
    );
  }

  /// `Showing`
  String get Showing {
    return Intl.message('Showing', name: 'Showing', desc: '', args: []);
  }

  /// `results`
  String get results {
    return Intl.message('results', name: 'results', desc: '', args: []);
  }

  /// `of`
  String get oof {
    return Intl.message('of', name: 'oof', desc: '', args: []);
  }

  /// `Guest Access`
  String get guestAccess {
    return Intl.message(
      'Guest Access',
      name: 'guestAccess',
      desc: '',
      args: [],
    );
  }

  /// `or`
  String get or {
    return Intl.message('or', name: 'or', desc: '', args: []);
  }

  /// `Dinner`
  String get dinner {
    return Intl.message('Dinner', name: 'dinner', desc: '', args: []);
  }

  /// `Download Quran App`
  String get downloadQuranApp {
    return Intl.message(
      'Download Quran App',
      name: 'downloadQuranApp',
      desc: '',
      args: [],
    );
  }

  /// `continue`
  String get continuee {
    return Intl.message('continue', name: 'continuee', desc: '', args: []);
  }

  /// `Breakfast`
  String get breakfast {
    return Intl.message('Breakfast', name: 'breakfast', desc: '', args: []);
  }

  /// `Lunch`
  String get lunch {
    return Intl.message('Lunch', name: 'lunch', desc: '', args: []);
  }

  /// `Ayahs`
  String get ayahs {
    return Intl.message('Ayahs', name: 'ayahs', desc: '', args: []);
  }

  /// `Search Surah Index ...`
  String get searchSurahIndex {
    return Intl.message(
      'Search Surah Index ...',
      name: 'searchSurahIndex',
      desc: '',
      args: [],
    );
  }

  /// `Surahs`
  String get surahs {
    return Intl.message('Surahs', name: 'surahs', desc: '', args: []);
  }

  /// `Currently downloading Surahes. Please wait...`
  String get currentlyDownloadingSurahes {
    return Intl.message(
      'Currently downloading Surahes. Please wait...',
      name: 'currentlyDownloadingSurahes',
      desc: '',
      args: [],
    );
  }

  /// `Failed to download Surahs. Please try again`
  String get failedDownloadingSurahes {
    return Intl.message(
      'Failed to download Surahs. Please try again',
      name: 'failedDownloadingSurahes',
      desc: '',
      args: [],
    );
  }

  /// `Surahs Index`
  String get surahsIndexs {
    return Intl.message(
      'Surahs Index',
      name: 'surahsIndexs',
      desc: '',
      args: [],
    );
  }

  /// `Tap to read`
  String get tapToRead {
    return Intl.message('Tap to read', name: 'tapToRead', desc: '', args: []);
  }

  /// `No results found`
  String get noResultsFound {
    return Intl.message(
      'No results found',
      name: 'noResultsFound',
      desc: '',
      args: [],
    );
  }

  /// `contact via WhatsApp`
  String get guestContactWhatsApp {
    return Intl.message(
      'contact via WhatsApp',
      name: 'guestContactWhatsApp',
      desc: '',
      args: [],
    );
  }

  /// `Welcome!`
  String get guestWelcome {
    return Intl.message('Welcome!', name: 'guestWelcome', desc: '', args: []);
  }

  /// `I am your smart assistant in TEAA app`
  String get guestAssistant {
    return Intl.message(
      'I am your smart assistant in TEAA app',
      name: 'guestAssistant',
      desc: '',
      args: [],
    );
  }

  /// `To access all amazing app features`
  String get guestUnlockFeatures {
    return Intl.message(
      'To access all amazing app features',
      name: 'guestUnlockFeatures',
      desc: '',
      args: [],
    );
  }

  /// `Please contact us to create your account`
  String get guestContactUs {
    return Intl.message(
      'Please contact us to create your account',
      name: 'guestContactUs',
      desc: '',
      args: [],
    );
  }

  /// `Contact via Email`
  String get guestContactEmail {
    return Intl.message(
      'Contact via Email',
      name: 'guestContactEmail',
      desc: '',
      args: [],
    );
  }

  /// `Downloading Quran data...`
  String get downloadingQuran {
    return Intl.message(
      'Downloading Quran data...',
      name: 'downloadingQuran',
      desc: '',
      args: [],
    );
  }

  /// `Please wait`
  String get pleaseWait {
    return Intl.message('Please wait', name: 'pleaseWait', desc: '', args: []);
  }

  /// `Call us directly`
  String get guestContactPhone {
    return Intl.message(
      'Call us directly',
      name: 'guestContactPhone',
      desc: '',
      args: [],
    );
  }

  /// `You will be able to access:`
  String get guestAccessTo {
    return Intl.message(
      'You will be able to access:',
      name: 'guestAccessTo',
      desc: '',
      args: [],
    );
  }

  /// `Meals`
  String get guestFeatureMeals {
    return Intl.message('Meals', name: 'guestFeatureMeals', desc: '', args: []);
  }

  /// `Groups`
  String get guestFeatureGroups {
    return Intl.message(
      'Groups',
      name: 'guestFeatureGroups',
      desc: '',
      args: [],
    );
  }

  /// `Residence`
  String get guestFeatureResidence {
    return Intl.message(
      'Residence',
      name: 'guestFeatureResidence',
      desc: '',
      args: [],
    );
  }

  /// `Activities`
  String get guestFeatureActivities {
    return Intl.message(
      'Activities',
      name: 'guestFeatureActivities',
      desc: '',
      args: [],
    );
  }

  /// `Listen to me`
  String get voiceListenToMe {
    return Intl.message(
      'Listen to me',
      name: 'voiceListenToMe',
      desc: '',
      args: [],
    );
  }

  /// `Listening...`
  String get voiceListening {
    return Intl.message(
      'Listening...',
      name: 'voiceListening',
      desc: '',
      args: [],
    );
  }

  /// `Tap to speak`
  String get voiceTapToSpeak {
    return Intl.message(
      'Tap to speak',
      name: 'voiceTapToSpeak',
      desc: '',
      args: [],
    );
  }

  /// `Stop listening`
  String get voiceStopListening {
    return Intl.message(
      'Stop listening',
      name: 'voiceStopListening',
      desc: '',
      args: [],
    );
  }

  /// `Processing...`
  String get voiceProcessing {
    return Intl.message(
      'Processing...',
      name: 'voiceProcessing',
      desc: '',
      args: [],
    );
  }

  /// `Microphone permission denied`
  String get voicePermissionDenied {
    return Intl.message(
      'Microphone permission denied',
      name: 'voicePermissionDenied',
      desc: '',
      args: [],
    );
  }

  /// `Voice recognition not available`
  String get voiceNotAvailable {
    return Intl.message(
      'Voice recognition not available',
      name: 'voiceNotAvailable',
      desc: '',
      args: [],
    );
  }

  /// `meal,hungry,food,eat,breakfast,lunch,dinner,snack,restaurant,cafe,dish,menu,order,cooking,recipe,appetite,starving,famished,craving,delicious,tasty,yummy,cuisine,dining,supper,brunch,dessert,beverage,drink,thirsty,feed,nutrition,diet,calories,protein,carbs,vegetables,fruits,meat,chicken,fish,rice,bread,pasta,pizza,burger,sandwich,salad,soup,chef,cook,kitchen,plate,spoon,fork,knife`
  String get mealKeywords {
    return Intl.message(
      'meal,hungry,food,eat,breakfast,lunch,dinner,snack,restaurant,cafe,dish,menu,order,cooking,recipe,appetite,starving,famished,craving,delicious,tasty,yummy,cuisine,dining,supper,brunch,dessert,beverage,drink,thirsty,feed,nutrition,diet,calories,protein,carbs,vegetables,fruits,meat,chicken,fish,rice,bread,pasta,pizza,burger,sandwich,salad,soup,chef,cook,kitchen,plate,spoon,fork,knife',
      name: 'mealKeywords',
      desc: '',
      args: [],
    );
  }

  /// `group,groups,community,team,members,join,people,friends,colleagues,squad,crew,gang,club,association,organization,society,circle,gathering,meeting,congregation,assembly,participants,attendees,roster,fellowship`
  String get groupsKeywords {
    return Intl.message(
      'group,groups,community,team,members,join,people,friends,colleagues,squad,crew,gang,club,association,organization,society,circle,gathering,meeting,congregation,assembly,participants,attendees,roster,fellowship',
      name: 'groupsKeywords',
      desc: '',
      args: [],
    );
  }

  /// `residence,home,place,house,accommodation,lodging,hotel,apartment,room,stay,living,dwelling,quarters,shelter,housing,address,location,building,suite,flat,villa,compound`
  String get residenceKeywords {
    return Intl.message(
      'residence,home,place,house,accommodation,lodging,hotel,apartment,room,stay,living,dwelling,quarters,shelter,housing,address,location,building,suite,flat,villa,compound',
      name: 'residenceKeywords',
      desc: '',
      args: [],
    );
  }

  /// `profile,account,settings,personal,info,information,details,data,user,me,my,preferences,configuration,options,edit,update,change,modify,myself,identity,credentials,biography`
  String get profileKeywords {
    return Intl.message(
      'profile,account,settings,personal,info,information,details,data,user,me,my,preferences,configuration,options,edit,update,change,modify,myself,identity,credentials,biography',
      name: 'profileKeywords',
      desc: '',
      args: [],
    );
  }

  /// `prayer,salah,mosque,azan,dua,worship,prostration,qibla,direction,fajr,dhuhr,asr,maghrib,isha,tahajjud,sunnah,nafl,rakat,imam,masjid,islamic,muslim,religion,spiritual,ablution,wudu,adhan,call,times`
  String get prayerKeywords {
    return Intl.message(
      'prayer,salah,mosque,azan,dua,worship,prostration,qibla,direction,fajr,dhuhr,asr,maghrib,isha,tahajjud,sunnah,nafl,rakat,imam,masjid,islamic,muslim,religion,spiritual,ablution,wudu,adhan,call,times',
      name: 'prayerKeywords',
      desc: '',
      args: [],
    );
  }

  /// `home,main,dashboard,start,beginning,initial,first,welcome,homepage,index,overview,summary,lobby,entrance,base,root`
  String get homeKeywords {
    return Intl.message(
      'home,main,dashboard,start,beginning,initial,first,welcome,homepage,index,overview,summary,lobby,entrance,base,root',
      name: 'homeKeywords',
      desc: '',
      args: [],
    );
  }

  /// `Attendance`
  String get attendance {
    return Intl.message('Attendance', name: 'attendance', desc: '', args: []);
  }

  /// `Records`
  String get records {
    return Intl.message('Records', name: 'records', desc: '', args: []);
  }

  /// `Recent Records`
  String get recentRecords {
    return Intl.message(
      'Recent Records',
      name: 'recentRecords',
      desc: '',
      args: [],
    );
  }

  /// `No Attendance Records`
  String get noAttendanceRecords {
    return Intl.message(
      'No Attendance Records',
      name: 'noAttendanceRecords',
      desc: '',
      args: [],
    );
  }

  /// `Your attendance records will appear here once they are checked in`
  String get noAttendanceRecordsDesc {
    return Intl.message(
      'Your attendance records will appear here once they are checked in',
      name: 'noAttendanceRecordsDesc',
      desc: '',
      args: [],
    );
  }

  /// `Your attendance records and check-in information will appear here`
  String get attendanceWillAppearHere {
    return Intl.message(
      'Your attendance records and check-in information will appear here',
      name: 'attendanceWillAppearHere',
      desc: '',
      args: [],
    );
  }

  /// `An error occurred`
  String get errorOccurred {
    return Intl.message(
      'An error occurred',
      name: 'errorOccurred',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Request`
  String get complaintRequest {
    return Intl.message(
      'Complaint Request',
      name: 'complaintRequest',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Type`
  String get complaintType {
    return Intl.message(
      'Complaint Type',
      name: 'complaintType',
      desc: '',
      args: [],
    );
  }

  /// `Select Complaint Type`
  String get selectComplaintType {
    return Intl.message(
      'Select Complaint Type',
      name: 'selectComplaintType',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Reason`
  String get complaintReason {
    return Intl.message(
      'Complaint Reason',
      name: 'complaintReason',
      desc: '',
      args: [],
    );
  }

  /// `Select Complaint Reason`
  String get selectComplaintReason {
    return Intl.message(
      'Select Complaint Reason',
      name: 'selectComplaintReason',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Description`
  String get complaintDescription {
    return Intl.message(
      'Complaint Description',
      name: 'complaintDescription',
      desc: '',
      args: [],
    );
  }

  /// `Describe your complaint in detail...`
  String get complaintDescriptionHint {
    return Intl.message(
      'Describe your complaint in detail...',
      name: 'complaintDescriptionHint',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Details`
  String get complaintRequestDetails {
    return Intl.message(
      'Complaint Details',
      name: 'complaintRequestDetails',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Type`
  String get complaintTypeLabel {
    return Intl.message(
      'Complaint Type',
      name: 'complaintTypeLabel',
      desc: '',
      args: [],
    );
  }

  /// `Complaint Reason`
  String get complaintReasonLabel {
    return Intl.message(
      'Complaint Reason',
      name: 'complaintReasonLabel',
      desc: '',
      args: [],
    );
  }

  /// `Description`
  String get complaintDescriptionLabel {
    return Intl.message(
      'Description',
      name: 'complaintDescriptionLabel',
      desc: '',
      args: [],
    );
  }

  /// `Edit Request`
  String get editRequest {
    return Intl.message(
      'Edit Request',
      name: 'editRequest',
      desc: '',
      args: [],
    );
  }

  /// `Edit Reasons`
  String get editReasons {
    return Intl.message(
      'Edit Reasons',
      name: 'editReasons',
      desc: '',
      args: [],
    );
  }

  /// `Rejection Reasons`
  String get rejectReasons {
    return Intl.message(
      'Rejection Reasons',
      name: 'rejectReasons',
      desc: '',
      args: [],
    );
  }

  /// `Request Updated Successfully`
  String get requestUpdatedSuccessfully {
    return Intl.message(
      'Request Updated Successfully',
      name: 'requestUpdatedSuccessfully',
      desc: '',
      args: [],
    );
  }

  /// `Update Request`
  String get updateRequest {
    return Intl.message(
      'Update Request',
      name: 'updateRequest',
      desc: '',
      args: [],
    );
  }

  /// `Study Type`
  String get studyType {
    return Intl.message('Study Type', name: 'studyType', desc: '', args: []);
  }

  /// `Select study type`
  String get selectStudyType {
    return Intl.message(
      'Select study type',
      name: 'selectStudyType',
      desc: '',
      args: [],
    );
  }

  /// `Study type is required`
  String get studyTypeRequired {
    return Intl.message(
      'Study type is required',
      name: 'studyTypeRequired',
      desc: '',
      args: [],
    );
  }

  /// `Required Study`
  String get requiredStudy {
    return Intl.message(
      'Required Study',
      name: 'requiredStudy',
      desc: '',
      args: [],
    );
  }

  /// `e.g. Physics`
  String get requiredStudyHint {
    return Intl.message(
      'e.g. Physics',
      name: 'requiredStudyHint',
      desc: '',
      args: [],
    );
  }

  /// `Destination / Entity`
  String get studyDestination {
    return Intl.message(
      'Destination / Entity',
      name: 'studyDestination',
      desc: '',
      args: [],
    );
  }

  /// `Select destination`
  String get selectStudyDestination {
    return Intl.message(
      'Select destination',
      name: 'selectStudyDestination',
      desc: '',
      args: [],
    );
  }

  /// `Destination is required`
  String get destinationRequired {
    return Intl.message(
      'Destination is required',
      name: 'destinationRequired',
      desc: '',
      args: [],
    );
  }

  /// `Start Date`
  String get startDate {
    return Intl.message('Start Date', name: 'startDate', desc: '', args: []);
  }

  /// `End Date`
  String get endDate {
    return Intl.message('End Date', name: 'endDate', desc: '', args: []);
  }

  /// `Request Justification`
  String get orderReason {
    return Intl.message(
      'Request Justification',
      name: 'orderReason',
      desc: '',
      args: [],
    );
  }

  /// `Enter the reason for this request`
  String get orderReasonHint {
    return Intl.message(
      'Enter the reason for this request',
      name: 'orderReasonHint',
      desc: '',
      args: [],
    );
  }

  /// `This field is required`
  String get thisFieldRequired {
    return Intl.message(
      'This field is required',
      name: 'thisFieldRequired',
      desc: '',
      args: [],
    );
  }

  /// `Start Work`
  String get startWork {
    return Intl.message('Start Work', name: 'startWork', desc: '', args: []);
  }

  /// `Start Work Type`
  String get startWorkType {
    return Intl.message(
      'Start Work Type',
      name: 'startWorkType',
      desc: '',
      args: [],
    );
  }

  /// `Select start work type`
  String get selectStartWorkType {
    return Intl.message(
      'Select start work type',
      name: 'selectStartWorkType',
      desc: '',
      args: [],
    );
  }

  /// `Employee`
  String get employee {
    return Intl.message('Employee', name: 'employee', desc: '', args: []);
  }

  /// `Select Employee`
  String get selectEmployee {
    return Intl.message(
      'Select Employee',
      name: 'selectEmployee',
      desc: '',
      args: [],
    );
  }

  /// `Write note here...`
  String get writeNoteHere {
    return Intl.message(
      'Write note here...',
      name: 'writeNoteHere',
      desc: '',
      args: [],
    );
  }

  /// `Experience Certificate`
  String get experienceCertificate {
    return Intl.message(
      'Experience Certificate',
      name: 'experienceCertificate',
      desc: '',
      args: [],
    );
  }

  /// `Reason for Certificate Request`
  String get certificateReason {
    return Intl.message(
      'Reason for Certificate Request',
      name: 'certificateReason',
      desc: '',
      args: [],
    );
  }

  /// `Select reason`
  String get selectCertificateReason {
    return Intl.message(
      'Select reason',
      name: 'selectCertificateReason',
      desc: '',
      args: [],
    );
  }

  /// `ID Document Add / Renewal Request`
  String get idRenewalDocument {
    return Intl.message(
      'ID Document Add / Renewal Request',
      name: 'idRenewalDocument',
      desc: '',
      args: [],
    );
  }

  /// `Select request type`
  String get selectRequestType {
    return Intl.message(
      'Select request type',
      name: 'selectRequestType',
      desc: '',
      args: [],
    );
  }

  /// `Document Type`
  String get documentType {
    return Intl.message(
      'Document Type',
      name: 'documentType',
      desc: '',
      args: [],
    );
  }

  /// `Select document type`
  String get selectDocumentType {
    return Intl.message(
      'Select document type',
      name: 'selectDocumentType',
      desc: '',
      args: [],
    );
  }

  /// `Issuing Country`
  String get issuingCountry {
    return Intl.message(
      'Issuing Country',
      name: 'issuingCountry',
      desc: '',
      args: [],
    );
  }

  /// `Select issuing country`
  String get selectIssuingCountry {
    return Intl.message(
      'Select issuing country',
      name: 'selectIssuingCountry',
      desc: '',
      args: [],
    );
  }

  /// `Document Number`
  String get documentNumber {
    return Intl.message(
      'Document Number',
      name: 'documentNumber',
      desc: '',
      args: [],
    );
  }

  /// `Issue Number`
  String get issueNumber {
    return Intl.message(
      'Issue Number',
      name: 'issueNumber',
      desc: '',
      args: [],
    );
  }

  /// `Issue Date`
  String get issueDate {
    return Intl.message('Issue Date', name: 'issueDate', desc: '', args: []);
  }

  /// `Matched`
  String get tabaq {
    return Intl.message('Matched', name: 'tabaq', desc: '', args: []);
  }

  /// `Under Sponsorship`
  String get hasKafala {
    return Intl.message(
      'Under Sponsorship',
      name: 'hasKafala',
      desc: '',
      args: [],
    );
  }

  /// `Sponsor Name`
  String get kafeelName {
    return Intl.message('Sponsor Name', name: 'kafeelName', desc: '', args: []);
  }

  /// `Passport Number`
  String get passportNumber {
    return Intl.message(
      'Passport Number',
      name: 'passportNumber',
      desc: '',
      args: [],
    );
  }

  /// `Passport Address`
  String get passportAddress {
    return Intl.message(
      'Passport Address',
      name: 'passportAddress',
      desc: '',
      args: [],
    );
  }

  /// `Family Card Number`
  String get familyCardNumber {
    return Intl.message(
      'Family Card Number',
      name: 'familyCardNumber',
      desc: '',
      args: [],
    );
  }

  /// `Driving License Number`
  String get drivingLicenseNumber {
    return Intl.message(
      'Driving License Number',
      name: 'drivingLicenseNumber',
      desc: '',
      args: [],
    );
  }

  /// `Document Data`
  String get documentData {
    return Intl.message(
      'Document Data',
      name: 'documentData',
      desc: '',
      args: [],
    );
  }

  /// `Medical Insurance Upgrade`
  String get medicalInsurance {
    return Intl.message(
      'Medical Insurance Upgrade',
      name: 'medicalInsurance',
      desc: '',
      args: [],
    );
  }

  /// `Insurance Class`
  String get insuranceClass {
    return Intl.message(
      'Insurance Class',
      name: 'insuranceClass',
      desc: '',
      args: [],
    );
  }

  /// `Select insurance class`
  String get selectInsuranceClass {
    return Intl.message(
      'Select insurance class',
      name: 'selectInsuranceClass',
      desc: '',
      args: [],
    );
  }

  /// `Include Family Members`
  String get includeFamilyMembers {
    return Intl.message(
      'Include Family Members',
      name: 'includeFamilyMembers',
      desc: '',
      args: [],
    );
  }

  /// `Family Members`
  String get familyMembers {
    return Intl.message(
      'Family Members',
      name: 'familyMembers',
      desc: '',
      args: [],
    );
  }

  /// `No family members found`
  String get noFamilyMembersFound {
    return Intl.message(
      'No family members found',
      name: 'noFamilyMembersFound',
      desc: '',
      args: [],
    );
  }

  /// `Reason for Upgrade`
  String get reasonForUpgrade {
    return Intl.message(
      'Reason for Upgrade',
      name: 'reasonForUpgrade',
      desc: '',
      args: [],
    );
  }

  /// `Enter the reason for upgrade`
  String get reasonForUpgradeHint {
    return Intl.message(
      'Enter the reason for upgrade',
      name: 'reasonForUpgradeHint',
      desc: '',
      args: [],
    );
  }

  /// `Enter notes (optional)`
  String get notesHint {
    return Intl.message(
      'Enter notes (optional)',
      name: 'notesHint',
      desc: '',
      args: [],
    );
  }

  /// `Home`
  String get homeTab {
    return Intl.message('Home', name: 'homeTab', desc: '', args: []);
  }

  /// `Buddies`
  String get buddiesTab {
    return Intl.message('Buddies', name: 'buddiesTab', desc: '', args: []);
  }

  /// `Workspaces`
  String get workspacesTab {
    return Intl.message(
      'Workspaces',
      name: 'workspacesTab',
      desc: '',
      args: [],
    );
  }

  /// `Profile`
  String get profileTab {
    return Intl.message('Profile', name: 'profileTab', desc: '', args: []);
  }

  /// `Find an Anis`
  String get searchForAnis {
    return Intl.message(
      'Find an Anis',
      name: 'searchForAnis',
      desc: '',
      args: [],
    );
  }

  /// `Scan QR`
  String get scanQrShort {
    return Intl.message('Scan QR', name: 'scanQrShort', desc: '', args: []);
  }

  /// `Today's Sessions`
  String get todaysSessions {
    return Intl.message(
      'Today\'s Sessions',
      name: 'todaysSessions',
      desc: '',
      args: [],
    );
  }

  /// `No sessions today`
  String get noSessionsToday {
    return Intl.message(
      'No sessions today',
      name: 'noSessionsToday',
      desc: '',
      args: [],
    );
  }

  /// `Silver Plan`
  String get silverSubscription {
    return Intl.message(
      'Silver Plan',
      name: 'silverSubscription',
      desc: '',
      args: [],
    );
  }

  /// `Gold Plan`
  String get goldSubscription {
    return Intl.message(
      'Gold Plan',
      name: 'goldSubscription',
      desc: '',
      args: [],
    );
  }

  /// `Free Plan`
  String get freeSubscription {
    return Intl.message(
      'Free Plan',
      name: 'freeSubscription',
      desc: '',
      args: [],
    );
  }

  /// `Expires on`
  String get subscriptionExpires {
    return Intl.message(
      'Expires on',
      name: 'subscriptionExpires',
      desc: '',
      args: [],
    );
  }

  /// `Join`
  String get joinSession {
    return Intl.message('Join', name: 'joinSession', desc: '', args: []);
  }

  /// `Leave Session`
  String get leaveSession {
    return Intl.message(
      'Leave Session',
      name: 'leaveSession',
      desc: '',
      args: [],
    );
  }

  /// `Session Full`
  String get sessionFull {
    return Intl.message(
      'Session Full',
      name: 'sessionFull',
      desc: '',
      args: [],
    );
  }

  /// `Session Ended`
  String get sessionEnded {
    return Intl.message(
      'Session Ended',
      name: 'sessionEnded',
      desc: '',
      args: [],
    );
  }

  /// `In Progress`
  String get sessionInProgress {
    return Intl.message(
      'In Progress',
      name: 'sessionInProgress',
      desc: '',
      args: [],
    );
  }

  /// `Session Duration`
  String get sessionDuration {
    return Intl.message(
      'Session Duration',
      name: 'sessionDuration',
      desc: '',
      args: [],
    );
  }

  /// `Nearby Workspaces`
  String get nearbyWorkspaces {
    return Intl.message(
      'Nearby Workspaces',
      name: 'nearbyWorkspaces',
      desc: '',
      args: [],
    );
  }

  /// `All Workspaces`
  String get allWorkspaces {
    return Intl.message(
      'All Workspaces',
      name: 'allWorkspaces',
      desc: '',
      args: [],
    );
  }

  /// `Workspace Check-in`
  String get workspaceCheckIn {
    return Intl.message(
      'Workspace Check-in',
      name: 'workspaceCheckIn',
      desc: '',
      args: [],
    );
  }

  /// `Confirm Check-in`
  String get confirmCheckIn {
    return Intl.message(
      'Confirm Check-in',
      name: 'confirmCheckIn',
      desc: '',
      args: [],
    );
  }

  /// `Checked in successfully`
  String get checkInSuccess {
    return Intl.message(
      'Checked in successfully',
      name: 'checkInSuccess',
      desc: '',
      args: [],
    );
  }

  /// `Checked out successfully`
  String get checkOutSuccess {
    return Intl.message(
      'Checked out successfully',
      name: 'checkOutSuccess',
      desc: '',
      args: [],
    );
  }

  /// `Scan the code to check in`
  String get scanToCheckIn {
    return Intl.message(
      'Scan the code to check in',
      name: 'scanToCheckIn',
      desc: '',
      args: [],
    );
  }

  /// `Study Hours Today`
  String get hoursStudiedToday {
    return Intl.message(
      'Study Hours Today',
      name: 'hoursStudiedToday',
      desc: '',
      args: [],
    );
  }

  /// `Total Hours`
  String get totalHours {
    return Intl.message('Total Hours', name: 'totalHours', desc: '', args: []);
  }

  /// `Sessions`
  String get sessionsCount {
    return Intl.message('Sessions', name: 'sessionsCount', desc: '', args: []);
  }

  /// `Weekly Goal`
  String get weeklyGoal {
    return Intl.message('Weekly Goal', name: 'weeklyGoal', desc: '', args: []);
  }

  /// `Busy`
  String get workspaceBusy {
    return Intl.message('Busy', name: 'workspaceBusy', desc: '', args: []);
  }

  /// `Full`
  String get workspaceFull {
    return Intl.message('Full', name: 'workspaceFull', desc: '', args: []);
  }

  /// `Available`
  String get workspaceOpen {
    return Intl.message('Available', name: 'workspaceOpen', desc: '', args: []);
  }

  /// `Capacity`
  String get workspaceCapacity {
    return Intl.message(
      'Capacity',
      name: 'workspaceCapacity',
      desc: '',
      args: [],
    );
  }

  /// `Open Now`
  String get openNow {
    return Intl.message('Open Now', name: 'openNow', desc: '', args: []);
  }

  /// `Closed`
  String get closedNow {
    return Intl.message('Closed', name: 'closedNow', desc: '', args: []);
  }

  /// `Opens at`
  String get opensAt {
    return Intl.message('Opens at', name: 'opensAt', desc: '', args: []);
  }

  /// `Closes at`
  String get closesAt {
    return Intl.message('Closes at', name: 'closesAt', desc: '', args: []);
  }

  /// `Distance`
  String get distance {
    return Intl.message('Distance', name: 'distance', desc: '', args: []);
  }

  /// `km`
  String get km {
    return Intl.message('km', name: 'km', desc: '', args: []);
  }

  /// `m`
  String get meters {
    return Intl.message('m', name: 'meters', desc: '', args: []);
  }

  /// `Find Buddy`
  String get findBuddy {
    return Intl.message('Find Buddy', name: 'findBuddy', desc: '', args: []);
  }

  /// `Study Buddy`
  String get studyBuddy {
    return Intl.message('Study Buddy', name: 'studyBuddy', desc: '', args: []);
  }

  /// `Available Now`
  String get availableNow {
    return Intl.message(
      'Available Now',
      name: 'availableNow',
      desc: '',
      args: [],
    );
  }

  /// `Online Now`
  String get onlineNow {
    return Intl.message('Online Now', name: 'onlineNow', desc: '', args: []);
  }

  /// `Last seen`
  String get lastSeen {
    return Intl.message('Last seen', name: 'lastSeen', desc: '', args: []);
  }

  /// `All`
  String get allFilter {
    return Intl.message('All', name: 'allFilter', desc: '', args: []);
  }

  /// `This Week`
  String get thisWeek {
    return Intl.message('This Week', name: 'thisWeek', desc: '', args: []);
  }

  /// `Matched Sessions`
  String get matchedSessions {
    return Intl.message(
      'Matched Sessions',
      name: 'matchedSessions',
      desc: '',
      args: [],
    );
  }

  /// `Send Request`
  String get sendRequest {
    return Intl.message(
      'Send Request',
      name: 'sendRequest',
      desc: '',
      args: [],
    );
  }

  /// `Request Sent`
  String get requestSent {
    return Intl.message(
      'Request Sent',
      name: 'requestSent',
      desc: '',
      args: [],
    );
  }

  /// `Subject`
  String get buddySubject {
    return Intl.message('Subject', name: 'buddySubject', desc: '', args: []);
  }

  /// `Level`
  String get buddyLevel {
    return Intl.message('Level', name: 'buddyLevel', desc: '', args: []);
  }

  /// `University`
  String get buddyUniversity {
    return Intl.message(
      'University',
      name: 'buddyUniversity',
      desc: '',
      args: [],
    );
  }

  /// `Find the right study partner for you`
  String get buddyScreenSubtitle {
    return Intl.message(
      'Find the right study partner for you',
      name: 'buddyScreenSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Search by university...`
  String get universityHint {
    return Intl.message(
      'Search by university...',
      name: 'universityHint',
      desc: '',
      args: [],
    );
  }

  /// `Search by subject...`
  String get subjectHint {
    return Intl.message(
      'Search by subject...',
      name: 'subjectHint',
      desc: '',
      args: [],
    );
  }

  /// `{count} results`
  String resultsCount(Object count) {
    return Intl.message(
      '$count results',
      name: 'resultsCount',
      desc: '',
      args: [count],
    );
  }

  /// `No buddies found`
  String get noBuddiesFound {
    return Intl.message(
      'No buddies found',
      name: 'noBuddiesFound',
      desc: '',
      args: [],
    );
  }

  /// `Try different filters`
  String get tryDifferentFilters {
    return Intl.message(
      'Try different filters',
      name: 'tryDifferentFilters',
      desc: '',
      args: [],
    );
  }

  /// `Studying now`
  String get studyingNow {
    return Intl.message(
      'Studying now',
      name: 'studyingNow',
      desc: '',
      args: [],
    );
  }

  /// `Edit Profile`
  String get editProfile {
    return Intl.message(
      'Edit Profile',
      name: 'editProfile',
      desc: '',
      args: [],
    );
  }

  /// `My Stats`
  String get myStats {
    return Intl.message('My Stats', name: 'myStats', desc: '', args: []);
  }

  /// `Total Study Hours`
  String get totalStudyHours {
    return Intl.message(
      'Total Study Hours',
      name: 'totalStudyHours',
      desc: '',
      args: [],
    );
  }

  /// `Day Streak`
  String get streakDays {
    return Intl.message('Day Streak', name: 'streakDays', desc: '', args: []);
  }

  /// `Badges Earned`
  String get badgesEarned {
    return Intl.message(
      'Badges Earned',
      name: 'badgesEarned',
      desc: '',
      args: [],
    );
  }

  /// `Are you sure you want to log out?`
  String get logoutConfirm {
    return Intl.message(
      'Are you sure you want to log out?',
      name: 'logoutConfirm',
      desc: '',
      args: [],
    );
  }

  /// `Find your perfect study spot`
  String get workspacesSubtitle {
    return Intl.message(
      'Find your perfect study spot',
      name: 'workspacesSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Nearby`
  String get nearbyFilter {
    return Intl.message('Nearby', name: 'nearbyFilter', desc: '', args: []);
  }

  /// `No workspaces found`
  String get noWorkspacesFound {
    return Intl.message(
      'No workspaces found',
      name: 'noWorkspacesFound',
      desc: '',
      args: [],
    );
  }

  /// `Days Left`
  String get daysLeft {
    return Intl.message('Days Left', name: 'daysLeft', desc: '', args: []);
  }

  /// `Good Morning`
  String get goodMorning {
    return Intl.message('Good Morning', name: 'goodMorning', desc: '', args: []);
  }

  /// `Good Afternoon`
  String get goodAfternoon {
    return Intl.message(
      'Good Afternoon',
      name: 'goodAfternoon',
      desc: '',
      args: [],
    );
  }

  /// `Good Evening`
  String get goodEvening {
    return Intl.message(
      'Good Evening',
      name: 'goodEvening',
      desc: '',
      args: [],
    );
  }

  /// `Upgrade Plan`
  String get upgradePlan {
    return Intl.message('Upgrade Plan', name: 'upgradePlan', desc: '', args: []);
  }

  /// `Manage Subscription`
  String get manageSubscription {
    return Intl.message(
      'Manage Subscription',
      name: 'manageSubscription',
      desc: '',
      args: [],
    );
  }

  /// `Wallet Balance`
  String get walletBalance {
    return Intl.message('Wallet Balance', name: 'walletBalance', desc: '', args: []);
  }

  /// `Current Plan`
  String get currentPlan {
    return Intl.message('Current Plan', name: 'currentPlan', desc: '', args: []);
  }

  /// `Change Language`
  String get changeLanguage {
    return Intl.message('Change Language', name: 'changeLanguage', desc: '', args: []);
  }
}

class AppLocalizationDelegate extends LocalizationsDelegate<S> {
  const AppLocalizationDelegate();

  List<Locale> get supportedLocales {
    return const <Locale>[
      Locale.fromSubtags(languageCode: 'en'),
      Locale.fromSubtags(languageCode: 'ar'),
    ];
  }

  @override
  bool isSupported(Locale locale) => _isSupported(locale);
  @override
  Future<S> load(Locale locale) => S.load(locale);
  @override
  bool shouldReload(AppLocalizationDelegate old) => false;

  bool _isSupported(Locale locale) {
    for (var supportedLocale in supportedLocales) {
      if (supportedLocale.languageCode == locale.languageCode) {
        return true;
      }
    }
    return false;
  }
}

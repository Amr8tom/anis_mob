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

  /// `Arafa Camps`
  String get ac {
    return Intl.message('Arafa Camps', name: 'ac', desc: '', args: []);
  }

  /// `Activities`
  String get activities {
    return Intl.message('Activities', name: 'activities', desc: '', args: []);
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

  /// `All`
  String get allFilter {
    return Intl.message('All', name: 'allFilter', desc: '', args: []);
  }

  /// `Already have an account?`
  String get alreadyHaveAccount {
    return Intl.message(
      'Already have an account?',
      name: 'alreadyHaveAccount',
      desc: '',
      args: [],
    );
  }

  /// `Amenities`
  String get amenities {
    return Intl.message('Amenities', name: 'amenities', desc: '', args: []);
  }

  /// `Air Conditioning`
  String get amenityAc {
    return Intl.message(
      'Air Conditioning',
      name: 'amenityAc',
      desc: '',
      args: [],
    );
  }

  /// `Coffee`
  String get amenityCoffee {
    return Intl.message('Coffee', name: 'amenityCoffee', desc: '', args: []);
  }

  /// `Printing`
  String get amenityPrinting {
    return Intl.message(
      'Printing',
      name: 'amenityPrinting',
      desc: '',
      args: [],
    );
  }

  /// `Quiet Area`
  String get amenityQuiet {
    return Intl.message('Quiet Area', name: 'amenityQuiet', desc: '', args: []);
  }

  /// `Wi-Fi`
  String get amenityWifi {
    return Intl.message('Wi-Fi', name: 'amenityWifi', desc: '', args: []);
  }

  /// `Book your ideal workspace with ease.`
  String get appDescription {
    return Intl.message(
      'Book your ideal workspace with ease.',
      name: 'appDescription',
      desc: '',
      args: [],
    );
  }

  /// `Anis`
  String get appName {
    return Intl.message('Anis', name: 'appName', desc: '', args: []);
  }

  /// `Study smarter together`
  String get appTagline {
    return Intl.message(
      'Study smarter together',
      name: 'appTagline',
      desc: '',
      args: [],
    );
  }

  /// `Asr`
  String get asr {
    return Intl.message('Asr', name: 'asr', desc: '', args: []);
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

  /// `User name or password is incorrect`
  String get authenticationError {
    return Intl.message(
      'User name or password is incorrect',
      name: 'authenticationError',
      desc: '',
      args: [],
    );
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

  /// `Badges Earned`
  String get badgesEarned {
    return Intl.message(
      'Badges Earned',
      name: 'badgesEarned',
      desc: '',
      args: [],
    );
  }

  /// `Be the first to start a session here!`
  String get beFirstToStartSession {
    return Intl.message(
      'Be the first to start a session here!',
      name: 'beFirstToStartSession',
      desc: '',
      args: [],
    );
  }

  /// `Buddies`
  String get buddiesTab {
    return Intl.message('Buddies', name: 'buddiesTab', desc: '', args: []);
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

  /// `By logging in you agree to our`
  String get byLoggingInYouAgree {
    return Intl.message(
      'By logging in you agree to our',
      name: 'byLoggingInYouAgree',
      desc: '',
      args: [],
    );
  }

  /// `Camera`
  String get camera {
    return Intl.message('Camera', name: 'camera', desc: '', args: []);
  }

  /// `Cancel`
  String get cancel {
    return Intl.message('Cancel', name: 'cancel', desc: '', args: []);
  }

  /// `Category`
  String get category {
    return Intl.message('Category', name: 'category', desc: '', args: []);
  }

  /// `Change Language`
  String get changeLanguage {
    return Intl.message(
      'Change Language',
      name: 'changeLanguage',
      desc: '',
      args: [],
    );
  }

  /// `Check In`
  String get checkIn {
    return Intl.message('Check In', name: 'checkIn', desc: '', args: []);
  }

  /// `Checked in successfully!`
  String get checkInSuccess {
    return Intl.message(
      'Checked in successfully!',
      name: 'checkInSuccess',
      desc: '',
      args: [],
    );
  }

  /// `Check Out`
  String get checkOut {
    return Intl.message('Check Out', name: 'checkOut', desc: '', args: []);
  }

  /// `Session saved. See you next time!`
  String get checkOutSuccess {
    return Intl.message(
      'Session saved. See you next time!',
      name: 'checkOutSuccess',
      desc: '',
      args: [],
    );
  }

  /// `checked In`
  String get checkedIn {
    return Intl.message('checked In', name: 'checkedIn', desc: '', args: []);
  }

  /// `checked Out`
  String get checkedOut {
    return Intl.message('checked Out', name: 'checkedOut', desc: '', args: []);
  }

  /// `Checking in...`
  String get checkingIn {
    return Intl.message(
      'Checking in...',
      name: 'checkingIn',
      desc: '',
      args: [],
    );
  }

  /// `Saving session...`
  String get checkingOut {
    return Intl.message(
      'Saving session...',
      name: 'checkingOut',
      desc: '',
      args: [],
    );
  }

  /// `Choose Your Plan`
  String get choosePlanTitle {
    return Intl.message(
      'Choose Your Plan',
      name: 'choosePlanTitle',
      desc: '',
      args: [],
    );
  }

  /// `Choose Your Gender`
  String get chooseYourGender {
    return Intl.message(
      'Choose Your Gender',
      name: 'chooseYourGender',
      desc: '',
      args: [],
    );
  }

  /// `Closed`
  String get closedNow {
    return Intl.message('Closed', name: 'closedNow', desc: '', args: []);
  }

  /// `Color`
  String get color {
    return Intl.message('Color', name: 'color', desc: '', args: []);
  }

  /// `Confirm Password`
  String get confirmPassword {
    return Intl.message(
      'Confirm Password',
      name: 'confirmPassword',
      desc: '',
      args: [],
    );
  }

  /// `Continue as Guest`
  String get continueAsGuest {
    return Intl.message(
      'Continue as Guest',
      name: 'continueAsGuest',
      desc: '',
      args: [],
    );
  }

  /// `continue`
  String get continuee {
    return Intl.message('continue', name: 'continuee', desc: '', args: []);
  }

  /// `Create account`
  String get createAccount {
    return Intl.message(
      'Create account',
      name: 'createAccount',
      desc: '',
      args: [],
    );
  }

  /// `Create Session`
  String get createSession {
    return Intl.message(
      'Create Session',
      name: 'createSession',
      desc: '',
      args: [],
    );
  }

  /// `Add Rule`
  String get createSessionAddRule {
    return Intl.message(
      'Add Rule',
      name: 'createSessionAddRule',
      desc: '',
      args: [],
    );
  }

  /// `Basic Info`
  String get createSessionBasicInfo {
    return Intl.message(
      'Basic Info',
      name: 'createSessionBasicInfo',
      desc: '',
      args: [],
    );
  }

  /// `Max Members`
  String get createSessionCapacity {
    return Intl.message(
      'Max Members',
      name: 'createSessionCapacity',
      desc: '',
      args: [],
    );
  }

  /// `What will you be working on?`
  String get createSessionDescHint {
    return Intl.message(
      'What will you be working on?',
      name: 'createSessionDescHint',
      desc: '',
      args: [],
    );
  }

  /// `Description`
  String get createSessionDescription {
    return Intl.message(
      'Description',
      name: 'createSessionDescription',
      desc: '',
      args: [],
    );
  }

  /// `e.g. Coffee, snacks...`
  String get createSessionGiftHint {
    return Intl.message(
      'e.g. Coffee, snacks...',
      name: 'createSessionGiftHint',
      desc: '',
      args: [],
    );
  }

  /// `Gift / Treat`
  String get createSessionGiftLabel {
    return Intl.message(
      'Gift / Treat',
      name: 'createSessionGiftLabel',
      desc: '',
      args: [],
    );
  }

  /// `persons`
  String get createSessionPersons {
    return Intl.message(
      'persons',
      name: 'createSessionPersons',
      desc: '',
      args: [],
    );
  }

  /// `e.g. No talking loudly`
  String get createSessionRuleHint {
    return Intl.message(
      'e.g. No talking loudly',
      name: 'createSessionRuleHint',
      desc: '',
      args: [],
    );
  }

  /// `Subject`
  String get createSessionSubject {
    return Intl.message(
      'Subject',
      name: 'createSessionSubject',
      desc: '',
      args: [],
    );
  }

  /// `e.g. Mathematics, Physics...`
  String get createSessionSubjectHint {
    return Intl.message(
      'e.g. Mathematics, Physics...',
      name: 'createSessionSubjectHint',
      desc: '',
      args: [],
    );
  }

  /// `Create Session`
  String get createSessionSubmit {
    return Intl.message(
      'Create Session',
      name: 'createSessionSubmit',
      desc: '',
      args: [],
    );
  }

  /// `Create a Study Session`
  String get createSessionTitle {
    return Intl.message(
      'Create a Study Session',
      name: 'createSessionTitle',
      desc: '',
      args: [],
    );
  }

  /// `Topic`
  String get createSessionTopic {
    return Intl.message(
      'Topic',
      name: 'createSessionTopic',
      desc: '',
      args: [],
    );
  }

  /// `e.g. Calculus Chapter 3`
  String get createSessionTopicHint {
    return Intl.message(
      'e.g. Calculus Chapter 3',
      name: 'createSessionTopicHint',
      desc: '',
      args: [],
    );
  }

  /// `Create your account`
  String get createYourAccount {
    return Intl.message(
      'Create your account',
      name: 'createYourAccount',
      desc: '',
      args: [],
    );
  }

  /// `Current Plan`
  String get currentPlan {
    return Intl.message(
      'Current Plan',
      name: 'currentPlan',
      desc: '',
      args: [],
    );
  }

  /// `Current Workspace`
  String get currentWorkspace {
    return Intl.message(
      'Current Workspace',
      name: 'currentWorkspace',
      desc: '',
      args: [],
    );
  }

  /// `date`
  String get date {
    return Intl.message('date', name: 'date', desc: '', args: []);
  }

  /// `{days} days left`
  String daysLeft(Object days) {
    return Intl.message(
      '$days days left',
      name: 'daysLeft',
      desc: '',
      args: [days],
    );
  }

  /// `Description`
  String get des {
    return Intl.message('Description', name: 'des', desc: '', args: []);
  }

  /// `Description`
  String get description {
    return Intl.message('Description', name: 'description', desc: '', args: []);
  }

  /// `Dhuhr`
  String get dhuhr {
    return Intl.message('Dhuhr', name: 'dhuhr', desc: '', args: []);
  }

  /// `Done`
  String get done {
    return Intl.message('Done', name: 'done', desc: '', args: []);
  }

  /// `Drinks Menu`
  String get drinksMenu {
    return Intl.message('Drinks Menu', name: 'drinksMenu', desc: '', args: []);
  }

  /// `Email`
  String get email {
    return Intl.message('Email', name: 'email', desc: '', args: []);
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

  /// `there is an error`
  String get error {
    return Intl.message('there is an error', name: 'error', desc: '', args: []);
  }

  /// `Excellent`
  String get excellent {
    return Intl.message('Excellent', name: 'excellent', desc: '', args: []);
  }

  /// `Fajr`
  String get fajr {
    return Intl.message('Fajr', name: 'fajr', desc: '', args: []);
  }

  /// `Feedback`
  String get feed {
    return Intl.message('Feedback', name: 'feed', desc: '', args: []);
  }

  /// `Female`
  String get female {
    return Intl.message('Female', name: 'female', desc: '', args: []);
  }

  /// `This field is required`
  String get fieldRequired {
    return Intl.message(
      'This field is required',
      name: 'fieldRequired',
      desc: '',
      args: [],
    );
  }

  /// `Today`
  String get filterToday {
    return Intl.message('Today', name: 'filterToday', desc: '', args: []);
  }

  /// `Find Buddy`
  String get findBuddy {
    return Intl.message('Find Buddy', name: 'findBuddy', desc: '', args: []);
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

  /// `Full Name`
  String get full {
    return Intl.message('Full Name', name: 'full', desc: '', args: []);
  }

  /// `Your full name`
  String get fullNameHint {
    return Intl.message(
      'Your full name',
      name: 'fullNameHint',
      desc: '',
      args: [],
    );
  }

  /// `Gallery`
  String get gallery {
    return Intl.message('Gallery', name: 'gallery', desc: '', args: []);
  }

  /// `Gender`
  String get gender {
    return Intl.message('Gender', name: 'gender', desc: '', args: []);
  }

  /// `Something went wrong. Please try again.`
  String get generalError {
    return Intl.message(
      'Something went wrong. Please try again.',
      name: 'generalError',
      desc: '',
      args: [],
    );
  }

  /// `Get Started`
  String get getStarted {
    return Intl.message('Get Started', name: 'getStarted', desc: '', args: []);
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

  /// `Good`
  String get good {
    return Intl.message('Good', name: 'good', desc: '', args: []);
  }

  /// `Good afternoon`
  String get goodAfternoon {
    return Intl.message(
      'Good afternoon',
      name: 'goodAfternoon',
      desc: '',
      args: [],
    );
  }

  /// `Good evening`
  String get goodEvening {
    return Intl.message(
      'Good evening',
      name: 'goodEvening',
      desc: '',
      args: [],
    );
  }

  /// `Good morning`
  String get goodMorning {
    return Intl.message(
      'Good morning',
      name: 'goodMorning',
      desc: '',
      args: [],
    );
  }

  /// `Groups`
  String get groups {
    return Intl.message('Groups', name: 'groups', desc: '', args: []);
  }

  /// `This feature is available to registered students. Join Anis for free to unlock study sessions, buddy matching, and premium workspaces.`
  String get guestRestrictedBody {
    return Intl.message(
      'This feature is available to registered students. Join Anis for free to unlock study sessions, buddy matching, and premium workspaces.',
      name: 'guestRestrictedBody',
      desc: '',
      args: [],
    );
  }

  /// `Members Only`
  String get guestRestrictedTitle {
    return Intl.message(
      'Members Only',
      name: 'guestRestrictedTitle',
      desc: '',
      args: [],
    );
  }

  /// `Hajj Activity`
  String get hajj {
    return Intl.message('Hajj Activity', name: 'hajj', desc: '', args: []);
  }

  /// `Home`
  String get home {
    return Intl.message('Home', name: 'home', desc: '', args: []);
  }

  /// `Home`
  String get homeTab {
    return Intl.message('Home', name: 'homeTab', desc: '', args: []);
  }

  /// `hour`
  String get hour {
    return Intl.message('hour', name: 'hour', desc: '', args: []);
  }

  /// `Hours`
  String get hours {
    return Intl.message('Hours', name: 'hours', desc: '', args: []);
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

  /// `In Progress`
  String get inProgress {
    return Intl.message('In Progress', name: 'inProgress', desc: '', args: []);
  }

  /// `Info`
  String get infoTab {
    return Intl.message('Info', name: 'infoTab', desc: '', args: []);
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

  /// `This QR code is not a valid Anis workspace.`
  String get invalidWorkspaceQr {
    return Intl.message(
      'This QR code is not a valid Anis workspace.',
      name: 'invalidWorkspaceQr',
      desc: '',
      args: [],
    );
  }

  /// `Isha`
  String get isha {
    return Intl.message('Isha', name: 'isha', desc: '', args: []);
  }

  /// `Join`
  String get joinSession {
    return Intl.message('Join', name: 'joinSession', desc: '', args: []);
  }

  /// `Juz`
  String get juz {
    return Intl.message('Juz', name: 'juz', desc: '', args: []);
  }

  /// `Kaaba Tawaf`
  String get kaaba {
    return Intl.message('Kaaba Tawaf', name: 'kaaba', desc: '', args: []);
  }

  /// `km`
  String get km {
    return Intl.message('km', name: 'km', desc: '', args: []);
  }

  /// `العربية`
  String get languageArabic {
    return Intl.message('العربية', name: 'languageArabic', desc: '', args: []);
  }

  /// `English`
  String get languageEnglish {
    return Intl.message('English', name: 'languageEnglish', desc: '', args: []);
  }

  /// `Last seen`
  String get lastSeen {
    return Intl.message('Last seen', name: 'lastSeen', desc: '', args: []);
  }

  /// `Later`
  String get later {
    return Intl.message('Later', name: 'later', desc: '', args: []);
  }

  /// `Logout from Workspace`
  String get leaveWorkspace {
    return Intl.message(
      'Logout from Workspace',
      name: 'leaveWorkspace',
      desc: '',
      args: [],
    );
  }

  /// `Request to leave`
  String get requestToLeave {
    return Intl.message(
      'Request to leave',
      name: 'requestToLeave',
      desc: '',
      args: [],
    );
  }

  /// `Sending request...`
  String get sendingCheckoutRequest {
    return Intl.message(
      'Sending request...',
      name: 'sendingCheckoutRequest',
      desc: '',
      args: [],
    );
  }

  /// `Awaiting approval to leave`
  String get awaitingCheckoutApproval {
    return Intl.message(
      'Awaiting approval to leave',
      name: 'awaitingCheckoutApproval',
      desc: '',
      args: [],
    );
  }

  /// `The workspace will approve your checkout shortly.`
  String get checkoutPendingSubtitle {
    return Intl.message(
      'The workspace will approve your checkout shortly.',
      name: 'checkoutPendingSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Checkout request sent`
  String get checkoutRequestSent {
    return Intl.message(
      'Checkout request sent',
      name: 'checkoutRequestSent',
      desc: '',
      args: [],
    );
  }

  /// `Live Now`
  String get liveNow {
    return Intl.message('Live Now', name: 'liveNow', desc: '', args: []);
  }

  /// `Loading...`
  String get loading {
    return Intl.message('Loading...', name: 'loading', desc: '', args: []);
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

  /// `Location service permission denied`
  String get locSer {
    return Intl.message(
      'Location service permission denied',
      name: 'locSer',
      desc: '',
      args: [],
    );
  }

  /// `Location`
  String get location {
    return Intl.message('Location', name: 'location', desc: '', args: []);
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

  /// `Log Out`
  String get logOut {
    return Intl.message('Log Out', name: 'logOut', desc: '', args: []);
  }

  /// `Login`
  String get login {
    return Intl.message('Login', name: 'login', desc: '', args: []);
  }

  /// `Login / Register`
  String get loginOrRegister {
    return Intl.message(
      'Login / Register',
      name: 'loginOrRegister',
      desc: '',
      args: [],
    );
  }

  /// `Logout`
  String get logout {
    return Intl.message('Logout', name: 'logout', desc: '', args: []);
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

  /// `Maghrib`
  String get maghrib {
    return Intl.message('Maghrib', name: 'maghrib', desc: '', args: []);
  }

  /// `Male`
  String get male {
    return Intl.message('Male', name: 'male', desc: '', args: []);
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

  /// `Interests`
  String get memberInterests {
    return Intl.message(
      'Interests',
      name: 'memberInterests',
      desc: '',
      args: [],
    );
  }

  /// `Rating`
  String get memberRating {
    return Intl.message('Rating', name: 'memberRating', desc: '', args: []);
  }

  /// `Name:`
  String get name {
    return Intl.message('Name:', name: 'name', desc: '', args: []);
  }

  /// `Name must be at least 2 characters`
  String get nameTooShortError {
    return Intl.message(
      'Name must be at least 2 characters',
      name: 'nameTooShortError',
      desc: '',
      args: [],
    );
  }

  /// `Your name appears on your profile and study sessions.`
  String get nameWillAppearOnProfile {
    return Intl.message(
      'Your name appears on your profile and study sessions.',
      name: 'nameWillAppearOnProfile',
      desc: '',
      args: [],
    );
  }

  /// `Nearby`
  String get nearbyFilter {
    return Intl.message('Nearby', name: 'nearbyFilter', desc: '', args: []);
  }

  /// `Next`
  String get next {
    return Intl.message('Next', name: 'next', desc: '', args: []);
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

  /// `There is no data here till now`
  String get noData {
    return Intl.message(
      'There is no data here till now',
      name: 'noData',
      desc: '',
      args: [],
    );
  }

  /// `No Rating`
  String get noRating {
    return Intl.message('No Rating', name: 'noRating', desc: '', args: []);
  }

  /// `No active sessions in this workspace`
  String get noSessionsInWorkspace {
    return Intl.message(
      'No active sessions in this workspace',
      name: 'noSessionsInWorkspace',
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

  /// `No workspaces found`
  String get noWorkspacesFound {
    return Intl.message(
      'No workspaces found',
      name: 'noWorkspacesFound',
      desc: '',
      args: [],
    );
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

  /// `Notification Settings`
  String get notificationPreferencesTitle {
    return Intl.message(
      'Notification Settings',
      name: 'notificationPreferencesTitle',
      desc: '',
      args: [],
    );
  }

  /// `Choose what you want to receive. Important reminders stay separate from offers so you do not need to disable everything.`
  String get notificationPreferencesSubtitle {
    return Intl.message(
      'Choose what you want to receive. Important reminders stay separate from offers so you do not need to disable everything.',
      name: 'notificationPreferencesSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Notification settings saved`
  String get notificationPreferencesSaved {
    return Intl.message(
      'Notification settings saved',
      name: 'notificationPreferencesSaved',
      desc: '',
      args: [],
    );
  }

  /// `Turn on notifications`
  String get notificationPermissionTitle {
    return Intl.message(
      'Turn on notifications',
      name: 'notificationPermissionTitle',
      desc: '',
      args: [],
    );
  }

  /// `Get session reminders, subscription alerts, and important workspace updates at the right time.`
  String get notificationPermissionBody {
    return Intl.message(
      'Get session reminders, subscription alerts, and important workspace updates at the right time.',
      name: 'notificationPermissionBody',
      desc: '',
      args: [],
    );
  }

  /// `Enable notifications`
  String get notificationPermissionButton {
    return Intl.message(
      'Enable notifications',
      name: 'notificationPermissionButton',
      desc: '',
      args: [],
    );
  }

  /// `Notifications are enabled`
  String get notificationPermissionEnabledTitle {
    return Intl.message(
      'Notifications are enabled',
      name: 'notificationPermissionEnabledTitle',
      desc: '',
      args: [],
    );
  }

  /// `You can choose exactly what you receive below.`
  String get notificationPermissionEnabledBody {
    return Intl.message(
      'You can choose exactly what you receive below.',
      name: 'notificationPermissionEnabledBody',
      desc: '',
      args: [],
    );
  }

  /// `Session reminders`
  String get notificationSessionReminders {
    return Intl.message(
      'Session reminders',
      name: 'notificationSessionReminders',
      desc: '',
      args: [],
    );
  }

  /// `Reminders before study sessions and private sessions.`
  String get notificationSessionRemindersDesc {
    return Intl.message(
      'Reminders before study sessions and private sessions.',
      name: 'notificationSessionRemindersDesc',
      desc: '',
      args: [],
    );
  }

  /// `Subscription alerts`
  String get notificationSubscriptionAlerts {
    return Intl.message(
      'Subscription alerts',
      name: 'notificationSubscriptionAlerts',
      desc: '',
      args: [],
    );
  }

  /// `Warnings before subscriptions expire or remaining hours become low.`
  String get notificationSubscriptionAlertsDesc {
    return Intl.message(
      'Warnings before subscriptions expire or remaining hours become low.',
      name: 'notificationSubscriptionAlertsDesc',
      desc: '',
      args: [],
    );
  }

  /// `Offers and marketing`
  String get notificationOffersMarketing {
    return Intl.message(
      'Offers and marketing',
      name: 'notificationOffersMarketing',
      desc: '',
      args: [],
    );
  }

  /// `Promotions, discounts, and non-essential campaigns.`
  String get notificationOffersMarketingDesc {
    return Intl.message(
      'Promotions, discounts, and non-essential campaigns.',
      name: 'notificationOffersMarketingDesc',
      desc: '',
      args: [],
    );
  }

  /// `Workspace updates`
  String get notificationWorkspaceUpdates {
    return Intl.message(
      'Workspace updates',
      name: 'notificationWorkspaceUpdates',
      desc: '',
      args: [],
    );
  }

  /// `Important updates from workspaces you visit.`
  String get notificationWorkspaceUpdatesDesc {
    return Intl.message(
      'Important updates from workspaces you visit.',
      name: 'notificationWorkspaceUpdatesDesc',
      desc: '',
      args: [],
    );
  }

  /// `With a single subscription, walk into any nearby workspace and study in a focused, distraction-free environment — no extra costs, ever.`
  String get onboarding1Desc {
    return Intl.message(
      'With a single subscription, walk into any nearby workspace and study in a focused, distraction-free environment — no extra costs, ever.',
      name: 'onboarding1Desc',
      desc: '',
      args: [],
    );
  }

  /// `One Pass. Every Space.`
  String get onboarding1Title {
    return Intl.message(
      'One Pass. Every Space.',
      name: 'onboarding1Title',
      desc: '',
      args: [],
    );
  }

  /// `Discover available sessions at workspaces near you. Join peers studying the same subject and make every hour count.`
  String get onboarding2Desc {
    return Intl.message(
      'Discover available sessions at workspaces near you. Join peers studying the same subject and make every hour count.',
      name: 'onboarding2Desc',
      desc: '',
      args: [],
    );
  }

  /// `Join Live Study Sessions`
  String get onboarding2Title {
    return Intl.message(
      'Join Live Study Sessions',
      name: 'onboarding2Title',
      desc: '',
      args: [],
    );
  }

  /// `Search for a study buddy who matches your pace, or join a group — study together, learn something new, grow as a team.`
  String get onboarding3Desc {
    return Intl.message(
      'Search for a study buddy who matches your pace, or join a group — study together, learn something new, grow as a team.',
      name: 'onboarding3Desc',
      desc: '',
      args: [],
    );
  }

  /// `Find Your Anis`
  String get onboarding3Title {
    return Intl.message(
      'Find Your Anis',
      name: 'onboarding3Title',
      desc: '',
      args: [],
    );
  }

  /// `Online Now`
  String get onlineNow {
    return Intl.message('Online Now', name: 'onlineNow', desc: '', args: []);
  }

  /// `Open Google Map`
  String get open {
    return Intl.message('Open Google Map', name: 'open', desc: '', args: []);
  }

  /// `Open in Maps`
  String get openInMaps {
    return Intl.message('Open in Maps', name: 'openInMaps', desc: '', args: []);
  }

  /// `Open Now`
  String get openNow {
    return Intl.message('Open Now', name: 'openNow', desc: '', args: []);
  }

  /// `Open spot`
  String get openSpot {
    return Intl.message('Open spot', name: 'openSpot', desc: '', args: []);
  }

  /// `OR`
  String get or {
    return Intl.message('OR', name: 'or', desc: '', args: []);
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

  /// `OTP`
  String get otp {
    return Intl.message('OTP', name: 'otp', desc: '', args: []);
  }

  /// `Password`
  String get password {
    return Intl.message('Password', name: 'password', desc: '', args: []);
  }

  /// `Password is required`
  String get passwordEmptyError {
    return Intl.message(
      'Password is required',
      name: 'passwordEmptyError',
      desc: '',
      args: [],
    );
  }

  /// `••••••••`
  String get passwordHint {
    return Intl.message('••••••••', name: 'passwordHint', desc: '', args: []);
  }

  /// `Password must include a number`
  String get passwordMissingNumberError {
    return Intl.message(
      'Password must include a number',
      name: 'passwordMissingNumberError',
      desc: '',
      args: [],
    );
  }

  /// `Passwords do not match`
  String get passwordsDoNotMatch {
    return Intl.message(
      'Passwords do not match',
      name: 'passwordsDoNotMatch',
      desc: '',
      args: [],
    );
  }

  /// `+20 1XX XXX XXXX`
  String get phoneHint {
    return Intl.message(
      '+20 1XX XXX XXXX',
      name: 'phoneHint',
      desc: '',
      args: [],
    );
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

  /// `Phone number is required`
  String get phoneRequired {
    return Intl.message(
      'Phone number is required',
      name: 'phoneRequired',
      desc: '',
      args: [],
    );
  }

  /// `WhatsApp Number`
  String get whatsAppNumber {
    return Intl.message(
      'WhatsApp Number',
      name: 'whatsAppNumber',
      desc: '',
      args: [],
    );
  }

  /// `+20 1XX XXX XXXX`
  String get whatsAppNumberHint {
    return Intl.message(
      '+20 1XX XXX XXXX',
      name: 'whatsAppNumberHint',
      desc: '',
      args: [],
    );
  }

  /// `WhatsApp number is required`
  String get whatsAppNumberRequired {
    return Intl.message(
      'WhatsApp number is required',
      name: 'whatsAppNumberRequired',
      desc: '',
      args: [],
    );
  }

  /// `Best Value`
  String get planBestValue {
    return Intl.message(
      'Best Value',
      name: 'planBestValue',
      desc: '',
      args: [],
    );
  }

  /// `Get Started Free`
  String get planCtaFree {
    return Intl.message(
      'Get Started Free',
      name: 'planCtaFree',
      desc: '',
      args: [],
    );
  }

  /// `Go Gold`
  String get planCtaGold {
    return Intl.message('Go Gold', name: 'planCtaGold', desc: '', args: []);
  }

  /// `Go Silver`
  String get planCtaSilver {
    return Intl.message('Go Silver', name: 'planCtaSilver', desc: '', args: []);
  }

  /// `Current`
  String get planCurrentBadge {
    return Intl.message(
      'Current',
      name: 'planCurrentBadge',
      desc: '',
      args: [],
    );
  }

  /// `Contains ads`
  String get planFeatureAds {
    return Intl.message(
      'Contains ads',
      name: 'planFeatureAds',
      desc: '',
      args: [],
    );
  }

  /// `Study analytics`
  String get planFeatureAnalytics {
    return Intl.message(
      'Study analytics',
      name: 'planFeatureAnalytics',
      desc: '',
      args: [],
    );
  }

  /// `Up to 3 buddies`
  String get planFeatureBuddies {
    return Intl.message(
      'Up to 3 buddies',
      name: 'planFeatureBuddies',
      desc: '',
      args: [],
    );
  }

  /// `No ads`
  String get planFeatureNoAds {
    return Intl.message('No ads', name: 'planFeatureNoAds', desc: '', args: []);
  }

  /// `Priority support`
  String get planFeaturePriority {
    return Intl.message(
      'Priority support',
      name: 'planFeaturePriority',
      desc: '',
      args: [],
    );
  }

  /// `Up to 2 sessions/month`
  String get planFeatureSessions2 {
    return Intl.message(
      'Up to 2 sessions/month',
      name: 'planFeatureSessions2',
      desc: '',
      args: [],
    );
  }

  /// `Limited workspaces`
  String get planFeatureWorkspaces {
    return Intl.message(
      'Limited workspaces',
      name: 'planFeatureWorkspaces',
      desc: '',
      args: [],
    );
  }

  /// `All workspaces`
  String get planFeatureWorkspacesUnlimited {
    return Intl.message(
      'All workspaces',
      name: 'planFeatureWorkspacesUnlimited',
      desc: '',
      args: [],
    );
  }

  /// `Free`
  String get planFreeTitle {
    return Intl.message('Free', name: 'planFreeTitle', desc: '', args: []);
  }

  /// `Gold`
  String get planGoldTitle {
    return Intl.message('Gold', name: 'planGoldTitle', desc: '', args: []);
  }

  /// `Most Popular`
  String get planMostPopular {
    return Intl.message(
      'Most Popular',
      name: 'planMostPopular',
      desc: '',
      args: [],
    );
  }

  /// `0 EGP`
  String get planPriceFree {
    return Intl.message('0 EGP', name: 'planPriceFree', desc: '', args: []);
  }

  /// `2,300 EGP/mo`
  String get planPriceGold {
    return Intl.message(
      '2,300 EGP/mo',
      name: 'planPriceGold',
      desc: '',
      args: [],
    );
  }

  /// `1,700 EGP/mo`
  String get planPriceSilver {
    return Intl.message(
      '1,700 EGP/mo',
      name: 'planPriceSilver',
      desc: '',
      args: [],
    );
  }

  /// `Silver`
  String get planSilverTitle {
    return Intl.message('Silver', name: 'planSilverTitle', desc: '', args: []);
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

  /// `please dont let this filed null`
  String get pleaseEndterValue {
    return Intl.message(
      'please dont let this filed null',
      name: 'pleaseEndterValue',
      desc: '',
      args: [],
    );
  }

  /// `Poor`
  String get poor {
    return Intl.message('Poor', name: 'poor', desc: '', args: []);
  }

  /// `Price`
  String get price {
    return Intl.message('Price', name: 'price', desc: '', args: []);
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

  /// `Profile`
  String get profile {
    return Intl.message('Profile', name: 'profile', desc: '', args: []);
  }

  /// `Profile`
  String get profileTab {
    return Intl.message('Profile', name: 'profileTab', desc: '', args: []);
  }

  /// `Register`
  String get register {
    return Intl.message('Register', name: 'register', desc: '', args: []);
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

  /// `{count} results`
  String resultsCount(Object count) {
    return Intl.message(
      '$count results',
      name: 'resultsCount',
      desc: '',
      args: [count],
    );
  }

  /// `Retry`
  String get retry {
    return Intl.message('Retry', name: 'retry', desc: '', args: []);
  }

  /// `Arrival Time`
  String get round {
    return Intl.message('Arrival Time', name: 'round', desc: '', args: []);
  }

  /// `Scan QR`
  String get scanQrShort {
    return Intl.message('Scan QR', name: 'scanQrShort', desc: '', args: []);
  }

  /// `Scan workspace QR to check in`
  String get scanQrToCheckIn {
    return Intl.message(
      'Scan workspace QR to check in',
      name: 'scanQrToCheckIn',
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

  /// `Subscribe Now`
  String get subscribe {
    return Intl.message('Subscribe Now', name: 'subscribe', desc: '', args: []);
  }

  /// `Search workspaces...`
  String get searchWorkspace {
    return Intl.message(
      'Search workspaces...',
      name: 'searchWorkspace',
      desc: '',
      args: [],
    );
  }

  /// `Please select your gender`
  String get selectGenderError {
    return Intl.message(
      'Please select your gender',
      name: 'selectGenderError',
      desc: '',
      args: [],
    );
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

  /// `Select Workspace`
  String get selectWorkspace {
    return Intl.message(
      'Select Workspace',
      name: 'selectWorkspace',
      desc: '',
      args: [],
    );
  }

  /// `Tap to choose a workspace`
  String get selectWorkspaceHint {
    return Intl.message(
      'Tap to choose a workspace',
      name: 'selectWorkspaceHint',
      desc: '',
      args: [],
    );
  }

  /// `Send`
  String get send {
    return Intl.message('Send', name: 'send', desc: '', args: []);
  }

  /// `verify OTP`
  String get sendOtp {
    return Intl.message('verify OTP', name: 'sendOtp', desc: '', args: []);
  }

  /// `Sending .....`
  String get sending {
    return Intl.message('Sending .....', name: 'sending', desc: '', args: []);
  }

  /// `services`
  String get services {
    return Intl.message('services', name: 'services', desc: '', args: []);
  }

  /// `Date`
  String get sessionDate {
    return Intl.message('Date', name: 'sessionDate', desc: '', args: []);
  }

  /// `About this session`
  String get sessionDescription {
    return Intl.message(
      'About this session',
      name: 'sessionDescription',
      desc: '',
      args: [],
    );
  }

  /// `Founder`
  String get sessionFounder {
    return Intl.message('Founder', name: 'sessionFounder', desc: '', args: []);
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

  /// `Gift`
  String get sessionGift {
    return Intl.message('Gift', name: 'sessionGift', desc: '', args: []);
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

  /// `Joined`
  String get sessionJoined {
    return Intl.message('Joined', name: 'sessionJoined', desc: '', args: []);
  }

  /// `Members`
  String get sessionMembers {
    return Intl.message('Members', name: 'sessionMembers', desc: '', args: []);
  }

  /// `Open`
  String get sessionOpen {
    return Intl.message('Open', name: 'sessionOpen', desc: '', args: []);
  }

  /// `Location`
  String get sessionPlace {
    return Intl.message('Location', name: 'sessionPlace', desc: '', args: []);
  }

  /// `Session Rules`
  String get sessionRules {
    return Intl.message(
      'Session Rules',
      name: 'sessionRules',
      desc: '',
      args: [],
    );
  }

  /// `Overview`
  String get sessionTabOverview {
    return Intl.message(
      'Overview',
      name: 'sessionTabOverview',
      desc: '',
      args: [],
    );
  }

  /// `People`
  String get sessionTabPeople {
    return Intl.message('People', name: 'sessionTabPeople', desc: '', args: []);
  }

  /// `Time`
  String get sessionTime {
    return Intl.message('Time', name: 'sessionTime', desc: '', args: []);
  }

  /// `Sessions`
  String get sessionsCount {
    return Intl.message('Sessions', name: 'sessionsCount', desc: '', args: []);
  }

  /// `Sessions`
  String get sessionsTab {
    return Intl.message('Sessions', name: 'sessionsTab', desc: '', args: []);
  }

  /// `Settings`
  String get settings {
    return Intl.message('Settings', name: 'settings', desc: '', args: []);
  }

  /// `Show All`
  String get show {
    return Intl.message('Show All', name: 'show', desc: '', args: []);
  }

  /// `Sign in`
  String get signIn {
    return Intl.message('Sign in', name: 'signIn', desc: '', args: []);
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

  /// `Size`
  String get size {
    return Intl.message('Size', name: 'size', desc: '', args: []);
  }

  /// `SKIP INTRO`
  String get skip {
    return Intl.message('SKIP INTRO', name: 'skip', desc: '', args: []);
  }

  /// `Specialization / Major`
  String get specialization {
    return Intl.message(
      'Specialization / Major',
      name: 'specialization',
      desc: '',
      args: [],
    );
  }

  /// `e.g. Computer Science, Medicine...`
  String get specializationHint {
    return Intl.message(
      'e.g. Computer Science, Medicine...',
      name: 'specializationHint',
      desc: '',
      args: [],
    );
  }

  /// `Arabic`
  String get splashArabicHint {
    return Intl.message('Arabic', name: 'splashArabicHint', desc: '', args: []);
  }

  /// `Choose your language`
  String get splashChooseLanguage {
    return Intl.message(
      'Choose your language',
      name: 'splashChooseLanguage',
      desc: '',
      args: [],
    );
  }

  /// `English`
  String get splashEnglishHint {
    return Intl.message(
      'English',
      name: 'splashEnglishHint',
      desc: '',
      args: [],
    );
  }

  /// `One subscription unlocks any nearby workspace, live study sessions, and the right study partner.`
  String get splashScreenText {
    return Intl.message(
      'One subscription unlocks any nearby workspace, live study sessions, and the right study partner.',
      name: 'splashScreenText',
      desc: '',
      args: [],
    );
  }

  /// `Workspace  ·  Session  ·  Buddy`
  String get splashTagline {
    return Intl.message(
      'Workspace  ·  Session  ·  Buddy',
      name: 'splashTagline',
      desc: '',
      args: [],
    );
  }

  /// `Anis`
  String get splashTitle {
    return Intl.message('Anis', name: 'splashTitle', desc: '', args: []);
  }

  /// `Start Now`
  String get startNow {
    return Intl.message('Start Now', name: 'startNow', desc: '', args: []);
  }

  /// `Status`
  String get status {
    return Intl.message('Status', name: 'status', desc: '', args: []);
  }

  /// `Step {step} of {total} • {label}`
  String stepIndicator(Object step, Object total, Object label) {
    return Intl.message(
      'Step $step of $total • $label',
      name: 'stepIndicator',
      desc: '',
      args: [step, total, label],
    );
  }

  /// `Day Streak`
  String get streakDays {
    return Intl.message('Day Streak', name: 'streakDays', desc: '', args: []);
  }

  /// `Study Buddy`
  String get studyBuddy {
    return Intl.message('Study Buddy', name: 'studyBuddy', desc: '', args: []);
  }

  /// `Study Time`
  String get studyTimeLabel {
    return Intl.message(
      'Study Time',
      name: 'studyTimeLabel',
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

  /// `Subscription days left`
  String get subscriptionDaysLeft {
    return Intl.message(
      'Subscription days left',
      name: 'subscriptionDaysLeft',
      desc: '',
      args: [],
    );
  }

  /// `{remaining}/{total} days`
  String subscriptionDaysProgressValue(Object remaining, Object total) {
    return Intl.message(
      '$remaining/$total days',
      name: 'subscriptionDaysProgressValue',
      desc: '',
      args: [remaining, total],
    );
  }

  /// `Subscription`
  String get subscriptionProgress {
    return Intl.message(
      'Subscription',
      name: 'subscriptionProgress',
      desc: '',
      args: [],
    );
  }

  /// `Success`
  String get success {
    return Intl.message('Success', name: 'success', desc: '', args: []);
  }

  /// `Sunrise`
  String get sunrise {
    return Intl.message('Sunrise', name: 'sunrise', desc: '', args: []);
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

  /// `This Week`
  String get thisWeek {
    return Intl.message('This Week', name: 'thisWeek', desc: '', args: []);
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

  /// `Today's Sessions`
  String get todaysSessions {
    return Intl.message(
      'Today\'s Sessions',
      name: 'todaysSessions',
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

  /// `Total Late Hours`
  String get totalLateHours {
    return Intl.message(
      'Total Late Hours',
      name: 'totalLateHours',
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

  /// `Total Study Hours`
  String get totalStudyHours {
    return Intl.message(
      'Total Study Hours',
      name: 'totalStudyHours',
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

  /// `Try different filters`
  String get tryDifferentFilters {
    return Intl.message(
      'Try different filters',
      name: 'tryDifferentFilters',
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

  /// `Type:`
  String get type {
    return Intl.message('Type:', name: 'type', desc: '', args: []);
  }

  /// `University`
  String get university {
    return Intl.message('University', name: 'university', desc: '', args: []);
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

  /// `Unknown`
  String get unknown {
    return Intl.message('Unknown', name: 'unknown', desc: '', args: []);
  }

  /// `Upcoming`
  String get upcoming {
    return Intl.message('Upcoming', name: 'upcoming', desc: '', args: []);
  }

  /// `Upcoming Sessions`
  String get upcomingSessions {
    return Intl.message(
      'Upcoming Sessions',
      name: 'upcomingSessions',
      desc: '',
      args: [],
    );
  }

  /// `Update`
  String get update {
    return Intl.message('Update', name: 'update', desc: '', args: []);
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

  /// `Upgrade Plan`
  String get upgradePlan {
    return Intl.message(
      'Upgrade Plan',
      name: 'upgradePlan',
      desc: '',
      args: [],
    );
  }

  /// `User Name`
  String get userName {
    return Intl.message('User Name', name: 'userName', desc: '', args: []);
  }

  /// `Very Good`
  String get veryGood {
    return Intl.message('Very Good', name: 'veryGood', desc: '', args: []);
  }

  /// `View all`
  String get viewAll {
    return Intl.message('View all', name: 'viewAll', desc: '', args: []);
  }

  /// `Waiting`
  String get waiting {
    return Intl.message('Waiting', name: 'waiting', desc: '', args: []);
  }

  /// `Welcome Back,`
  String get welcome {
    return Intl.message('Welcome Back,', name: 'welcome', desc: '', args: []);
  }

  /// `Busy`
  String get workspaceBusy {
    return Intl.message('Busy', name: 'workspaceBusy', desc: '', args: []);
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

  /// `How this workspace counts a day`
  String get workspaceDayCalculationTitle {
    return Intl.message(
      'How this workspace counts a day',
      name: 'workspaceDayCalculationTitle',
      desc: '',
      args: [],
    );
  }

  /// `Full`
  String get workspaceFull {
    return Intl.message('Full', name: 'workspaceFull', desc: '', args: []);
  }

  /// `Available`
  String get workspaceOpen {
    return Intl.message('Available', name: 'workspaceOpen', desc: '', args: []);
  }

  /// `Find the perfect spot to study with your buddy`
  String get workspacesSubtitle {
    return Intl.message(
      'Find the perfect spot to study with your buddy',
      name: 'workspacesSubtitle',
      desc: '',
      args: [],
    );
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

  /// `Your Account`
  String get yourAccount {
    return Intl.message(
      'Your Account',
      name: 'yourAccount',
      desc: '',
      args: [],
    );
  }

  /// `Add`
  String get add {
    return Intl.message('Add', name: 'add', desc: '', args: []);
  }

  /// `Add another interest`
  String get addAnotherInterest {
    return Intl.message(
      'Add another interest',
      name: 'addAnotherInterest',
      desc: '',
      args: [],
    );
  }

  /// `Choose at least one interest`
  String get addInterestError {
    return Intl.message(
      'Choose at least one interest',
      name: 'addInterestError',
      desc: '',
      args: [],
    );
  }

  /// `Make Anis feel like yours`
  String get completeProfileTitle {
    return Intl.message(
      'Make Anis feel like yours',
      name: 'completeProfileTitle',
      desc: '',
      args: [],
    );
  }

  /// `Tell us a little about your studies so we can recommend better sessions and study buddies.`
  String get completeProfileSubtitle {
    return Intl.message(
      'Tell us a little about your studies so we can recommend better sessions and study buddies.',
      name: 'completeProfileSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Do it later`
  String get doItLater {
    return Intl.message('Do it later', name: 'doItLater', desc: '', args: []);
  }

  /// `Engineering`
  String get interestEngineering {
    return Intl.message(
      'Engineering',
      name: 'interestEngineering',
      desc: '',
      args: [],
    );
  }

  /// `For example: Design`
  String get interestHint {
    return Intl.message(
      'For example: Design',
      name: 'interestHint',
      desc: '',
      args: [],
    );
  }

  /// `Languages`
  String get interestLanguages {
    return Intl.message(
      'Languages',
      name: 'interestLanguages',
      desc: '',
      args: [],
    );
  }

  /// `Mathematics`
  String get interestMathematics {
    return Intl.message(
      'Mathematics',
      name: 'interestMathematics',
      desc: '',
      args: [],
    );
  }

  /// `Medicine`
  String get interestMedicine {
    return Intl.message(
      'Medicine',
      name: 'interestMedicine',
      desc: '',
      args: [],
    );
  }

  /// `Programming`
  String get interestProgramming {
    return Intl.message(
      'Programming',
      name: 'interestProgramming',
      desc: '',
      args: [],
    );
  }

  /// `Study interests`
  String get interests {
    return Intl.message(
      'Study interests',
      name: 'interests',
      desc: '',
      args: [],
    );
  }

  /// `you@university.edu.eg`
  String get profileEmailHint {
    return Intl.message(
      'you@university.edu.eg',
      name: 'profileEmailHint',
      desc: '',
      args: [],
    );
  }

  /// `Helps personalize your profile and recommendations.`
  String get profileGenderReason {
    return Intl.message(
      'Helps personalize your profile and recommendations.',
      name: 'profileGenderReason',
      desc: '',
      args: [],
    );
  }

  /// `Choose topics you would enjoy studying with others.`
  String get profileInterestsReason {
    return Intl.message(
      'Choose topics you would enjoy studying with others.',
      name: 'profileInterestsReason',
      desc: '',
      args: [],
    );
  }

  /// `Your university or institute`
  String get profileUniversityHint {
    return Intl.message(
      'Your university or institute',
      name: 'profileUniversityHint',
      desc: '',
      args: [],
    );
  }

  /// `Save and continue`
  String get saveAndContinue {
    return Intl.message(
      'Save and continue',
      name: 'saveAndContinue',
      desc: '',
      args: [],
    );
  }

  /// `Sign in to build your study profile`
  String get signInToSeeProfile {
    return Intl.message(
      'Sign in to build your study profile',
      name: 'signInToSeeProfile',
      desc: '',
      args: [],
    );
  }

  /// `Your profile, progress, badges, and subscription details are available after signing in.`
  String get signInToSeeProfileSubtitle {
    return Intl.message(
      'Your profile, progress, badges, and subscription details are available after signing in.',
      name: 'signInToSeeProfileSubtitle',
      desc: '',
      args: [],
    );
  }

  /// `Use at least 8 characters with uppercase, lowercase, and a number.`
  String get strongPasswordHint {
    return Intl.message(
      'Use at least 8 characters with uppercase, lowercase, and a number.',
      name: 'strongPasswordHint',
      desc: '',
      args: [],
    );
  }

  /// `120 hours/month`
  String get planFeatureSilverHours {
    return Intl.message(
      '120 hours/month',
      name: 'planFeatureSilverHours',
      desc: '',
      args: [],
    );
  }

  /// `200 hours/month`
  String get planFeatureGoldHours {
    return Intl.message(
      '200 hours/month',
      name: 'planFeatureGoldHours',
      desc: '',
      args: [],
    );
  }

  /// `Standard workspace (1 real hour = 1 subscription hour)`
  String get workspaceHourMultiplierStandard {
    return Intl.message(
      'Standard workspace (1 real hour = 1 subscription hour)',
      name: 'workspaceHourMultiplierStandard',
      desc: '',
      args: [],
    );
  }

  /// `Premium workspace (1 real hour = 2 subscription hours)`
  String get workspaceHourMultiplierPremium {
    return Intl.message(
      'Premium workspace (1 real hour = 2 subscription hours)',
      name: 'workspaceHourMultiplierPremium',
      desc: '',
      args: [],
    );
  }

  /// `1 real hour = {multiplier} subscription hours`
  String workspaceHourMultiplierCustom(Object multiplier) {
    return Intl.message(
      '1 real hour = $multiplier subscription hours',
      name: 'workspaceHourMultiplierCustom',
      desc: '',
      args: [multiplier],
    );
  }

  /// `Free workspace (1 real hour = 0 subscription hours)`
  String get workspaceHourMultiplierFree {
    return Intl.message(
      'Free workspace (1 real hour = 0 subscription hours)',
      name: 'workspaceHourMultiplierFree',
      desc: '',
      args: [],
    );
  }

  /// `Daily maximum deduction: {capHours} subscription hours (equivalent to {realHours} real hours)`
  String workspaceDailyCapText(Object capHours, Object realHours) {
    return Intl.message(
      'Daily maximum deduction: $capHours subscription hours (equivalent to $realHours real hours)',
      name: 'workspaceDailyCapText',
      desc: '',
      args: [capHours, realHours],
    );
  }

  /// `Turkish`
  String get languageTurkish {
    return Intl.message('Turkish', name: 'languageTurkish', desc: '', args: []);
  }

  /// `Plan activated successfully`
  String get planActivatedSuccessfully {
    return Intl.message(
      'Plan activated successfully',
      name: 'planActivatedSuccessfully',
      desc: '',
      args: [],
    );
  }

  /// `Hello, I want to purchase the {planName} plan in Anis.`
  String purchasePlanMessage(String planName) {
    return Intl.message(
      'Hello, I want to purchase the $planName plan in Anis.',
      name: 'purchasePlanMessage',
      desc: '',
      args: [planName],
    );
  }

  /// `Purchase the plan manually`
  String get purchasePlanManually {
    return Intl.message(
      'Purchase the plan manually',
      name: 'purchasePlanManually',
      desc: '',
      args: [],
    );
  }

  /// `Contact us and pay in cash. After receiving payment, we will send your activation code. Bank payment is coming later.`
  String get purchasePlanManualDescription {
    return Intl.message(
      'Contact us and pay in cash. After receiving payment, we will send your activation code. Bank payment is coming later.',
      name: 'purchasePlanManualDescription',
      desc: '',
      args: [],
    );
  }

  /// `WhatsApp {phone}`
  String whatsappNumber(String phone) {
    return Intl.message(
      'WhatsApp $phone',
      name: 'whatsappNumber',
      desc: '',
      args: [phone],
    );
  }

  /// `Call us`
  String get callUs {
    return Intl.message('Call us', name: 'callUs', desc: '', args: []);
  }

  /// `Facebook page`
  String get facebookPage {
    return Intl.message(
      'Facebook page',
      name: 'facebookPage',
      desc: '',
      args: [],
    );
  }

  /// `I have an activation code`
  String get haveActivationCode {
    return Intl.message(
      'I have an activation code',
      name: 'haveActivationCode',
      desc: '',
      args: [],
    );
  }

  /// `Activate plan code`
  String get activatePlanCode {
    return Intl.message(
      'Activate plan code',
      name: 'activatePlanCode',
      desc: '',
      args: [],
    );
  }

  /// `Enter the code you received`
  String get enterActivationCodeHint {
    return Intl.message(
      'Enter the code you received',
      name: 'enterActivationCodeHint',
      desc: '',
      args: [],
    );
  }

  /// `Activate`
  String get activate {
    return Intl.message('Activate', name: 'activate', desc: '', args: []);
  }

  /// `Received a plan code? Activate it here.`
  String get activationBannerDesc {
    return Intl.message(
      'Received a plan code? Activate it here.',
      name: 'activationBannerDesc',
      desc: '',
      args: [],
    );
  }

  /// `Activate code`
  String get activateCode {
    return Intl.message(
      'Activate code',
      name: 'activateCode',
      desc: '',
      args: [],
    );
  }

  /// `Locating...`
  String get locating {
    return Intl.message('Locating...', name: 'locating', desc: '', args: []);
  }

  /// `Locate via GPS`
  String get locateViaGps {
    return Intl.message(
      'Locate via GPS',
      name: 'locateViaGps',
      desc: '',
      args: [],
    );
  }

  /// `Locate yourself`
  String get locateYourself {
    return Intl.message(
      'Locate yourself',
      name: 'locateYourself',
      desc: '',
      args: [],
    );
  }

  /// `Choose your location so we can show you the nearest workspaces and accurately calculate distances`
  String get locateYourselfDesc {
    return Intl.message(
      'Choose your location so we can show you the nearest workspaces and accurately calculate distances',
      name: 'locateYourselfDesc',
      desc: '',
      args: [],
    );
  }

  /// `Or choose a region manually`
  String get orChooseRegionManually {
    return Intl.message(
      'Or choose a region manually',
      name: 'orChooseRegionManually',
      desc: '',
      args: [],
    );
  }

  /// `Dokki, Giza`
  String get regionDokki {
    return Intl.message('Dokki, Giza', name: 'regionDokki', desc: '', args: []);
  }

  /// `5th Settlement, Cairo`
  String get regionFifthSettlement {
    return Intl.message(
      '5th Settlement, Cairo',
      name: 'regionFifthSettlement',
      desc: '',
      args: [],
    );
  }

  /// `Nasr City, Cairo`
  String get regionNasrCity {
    return Intl.message(
      'Nasr City, Cairo',
      name: 'regionNasrCity',
      desc: '',
      args: [],
    );
  }

  /// `6th of October, Giza`
  String get regionOctober {
    return Intl.message(
      '6th of October, Giza',
      name: 'regionOctober',
      desc: '',
      args: [],
    );
  }

  /// `Smouha, Alexandria`
  String get regionSmouha {
    return Intl.message(
      'Smouha, Alexandria',
      name: 'regionSmouha',
      desc: '',
      args: [],
    );
  }

  /// `{days} days left • {hours} hours left`
  String subscriptionDaysAndHours(int days, int hours) {
    return Intl.message(
      '$days days left • $hours hours left',
      name: 'subscriptionDaysAndHours',
      desc: '',
      args: [days, hours],
    );
  }

  /// `{days} days used`
  String subscriptionDaysUsed(int days) {
    return Intl.message(
      '$days days used',
      name: 'subscriptionDaysUsed',
      desc: '',
      args: [days],
    );
  }

  /// `{days} days`
  String subscriptionTotalDaysLabel(int days) {
    return Intl.message(
      '$days days',
      name: 'subscriptionTotalDaysLabel',
      desc: '',
      args: [days],
    );
  }

  /// `Owner Panel`
  String get ownerPanelTitle {
    return Intl.message(
      'Owner Panel',
      name: 'ownerPanelTitle',
      desc: '',
      args: [],
    );
  }

  /// `Admin Panel`
  String get adminPanelTitle {
    return Intl.message(
      'Admin Panel',
      name: 'adminPanelTitle',
      desc: '',
      args: [],
    );
  }

  /// `Workspace Settings`
  String get workspaceSettingsTitle {
    return Intl.message(
      'Workspace Settings',
      name: 'workspaceSettingsTitle',
      desc: '',
      args: [],
    );
  }

  /// `Hour Multiplier`
  String get hourMultiplierLabel {
    return Intl.message(
      'Hour Multiplier',
      name: 'hourMultiplierLabel',
      desc: '',
      args: [],
    );
  }

  /// `Study Day Hours`
  String get dayCalculationHoursLabel {
    return Intl.message(
      'Study Day Hours',
      name: 'dayCalculationHoursLabel',
      desc: '',
      args: [],
    );
  }

  /// `Save Settings`
  String get saveSettings {
    return Intl.message(
      'Save Settings',
      name: 'saveSettings',
      desc: '',
      args: [],
    );
  }

  /// `Settings saved successfully`
  String get settingsSaved {
    return Intl.message(
      'Settings saved successfully',
      name: 'settingsSaved',
      desc: '',
      args: [],
    );
  }

  /// `Saving...`
  String get savingSettings {
    return Intl.message(
      'Saving...',
      name: 'savingSettings',
      desc: '',
      args: [],
    );
  }

  /// `Each real attendance hour is counted at this multiplier against the student's subscription balance.`
  String get hourMultiplierExplain {
    return Intl.message(
      'Each real attendance hour is counted at this multiplier against the student\'s subscription balance.',
      name: 'hourMultiplierExplain',
      desc: '',
      args: [],
    );
  }

  /// `Number of attendance hours that equals one full subscription day.`
  String get dayHoursExplain {
    return Intl.message(
      'Number of attendance hours that equals one full subscription day.',
      name: 'dayHoursExplain',
      desc: '',
      args: [],
    );
  }

  /// `Free  (0×)`
  String get freeWorkspaceLabel {
    return Intl.message(
      'Free  (0×)',
      name: 'freeWorkspaceLabel',
      desc: '',
      args: [],
    );
  }

  /// `Standard  (1×)`
  String get standardWorkspaceLabel {
    return Intl.message(
      'Standard  (1×)',
      name: 'standardWorkspaceLabel',
      desc: '',
      args: [],
    );
  }

  /// `Premium  (2×)`
  String get premiumWorkspaceLabel {
    return Intl.message(
      'Premium  (2×)',
      name: 'premiumWorkspaceLabel',
      desc: '',
      args: [],
    );
  }

  /// `Custom`
  String get customMultiplierLabel {
    return Intl.message(
      'Custom',
      name: 'customMultiplierLabel',
      desc: '',
      args: [],
    );
  }

  /// `Edit Settings`
  String get editSettings {
    return Intl.message(
      'Edit Settings',
      name: 'editSettings',
      desc: '',
      args: [],
    );
  }

  /// `My Workspace`
  String get myWorkspace {
    return Intl.message(
      'My Workspace',
      name: 'myWorkspace',
      desc: '',
      args: [],
    );
  }

  /// `Current Settings`
  String get currentSettings {
    return Intl.message(
      'Current Settings',
      name: 'currentSettings',
      desc: '',
      args: [],
    );
  }

  /// `1 hr attendance = {val} subscription hr | 1 day = {hours} hrs`
  String previewNote(String val, int hours) {
    return Intl.message(
      '1 hr attendance = $val subscription hr | 1 day = $hours hrs',
      name: 'previewNote',
      desc: '',
      args: [val, hours],
    );
  }
}

class AppLocalizationDelegate extends LocalizationsDelegate<S> {
  const AppLocalizationDelegate();

  List<Locale> get supportedLocales {
    return const <Locale>[
      Locale.fromSubtags(languageCode: 'en'),
      Locale.fromSubtags(languageCode: 'ar'),
      Locale.fromSubtags(languageCode: 'tr'),
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

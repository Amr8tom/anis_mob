// DO NOT EDIT. This is code generated via package:intl/generate_localized.dart
// This is a library that provides messages for a en locale. All the
// messages from the main program should be duplicated here with the same
// function name.

// Ignore issues from commonly used lints in this file.
// ignore_for_file:unnecessary_brace_in_string_interps, unnecessary_new
// ignore_for_file:prefer_single_quotes,comment_references, directives_ordering
// ignore_for_file:annotate_overrides,prefer_generic_function_type_aliases
// ignore_for_file:unused_import, file_names, avoid_escaping_inner_quotes
// ignore_for_file:unnecessary_string_interpolations, unnecessary_string_escapes

import 'package:intl/intl.dart';
import 'package:intl/message_lookup_by_library.dart';

final messages = new MessageLookup();

typedef String MessageIfAbsent(String messageStr, List<dynamic> args);

class MessageLookup extends MessageLookupByLibrary {
  String get localeName => 'en';

  static String m0(days) => "${days} days left";

  static String m1(val, hours) =>
      "1 hr attendance = ${val} subscription hr | 1 day = ${hours} hrs";

  static String m2(planName) =>
      "Hello, I want to purchase the ${planName} plan in Anis.";

  static String m3(count) => "${count} results";

  static String m4(step, total, label) => "Step ${step} of ${total} • ${label}";

  static String m5(days, hours) => "${days} days left • ${hours} hours left";

  static String m6(remaining, total) => "${remaining}/${total} days";

  static String m7(days) => "${days} days used";

  static String m8(days) => "${days} days";

  static String m9(phone) => "WhatsApp ${phone}";

  static String m10(capHours, realHours) =>
      "Daily maximum deduction: ${capHours} subscription hours (equivalent to ${realHours} real hours)";

  static String m11(multiplier) =>
      "1 real hour = ${multiplier} subscription hours";

  final messages = _notInlinedMessages(_notInlinedMessages);
  static Map<String, Function> _notInlinedMessages(_) => <String, Function>{
    "ac": MessageLookupByLibrary.simpleMessage("Arafa Camps"),
    "activate": MessageLookupByLibrary.simpleMessage("Activate"),
    "activateCode": MessageLookupByLibrary.simpleMessage("Activate code"),
    "activatePlanCode": MessageLookupByLibrary.simpleMessage(
      "Activate plan code",
    ),
    "activationBannerDesc": MessageLookupByLibrary.simpleMessage(
      "Received a plan code? Activate it here.",
    ),
    "activities": MessageLookupByLibrary.simpleMessage("Activities"),
    "add": MessageLookupByLibrary.simpleMessage("Add"),
    "addAnotherInterest": MessageLookupByLibrary.simpleMessage(
      "Add another interest",
    ),
    "addInterestError": MessageLookupByLibrary.simpleMessage(
      "Choose at least one interest",
    ),
    "address": MessageLookupByLibrary.simpleMessage("Shipping Address"),
    "adminPanelTitle": MessageLookupByLibrary.simpleMessage("Admin Panel"),
    "allFilter": MessageLookupByLibrary.simpleMessage("All"),
    "alreadyHaveAccount": MessageLookupByLibrary.simpleMessage(
      "Already have an account?",
    ),
    "amenities": MessageLookupByLibrary.simpleMessage("Amenities"),
    "amenityAc": MessageLookupByLibrary.simpleMessage("Air Conditioning"),
    "amenityCoffee": MessageLookupByLibrary.simpleMessage("Coffee"),
    "amenityPrinting": MessageLookupByLibrary.simpleMessage("Printing"),
    "amenityQuiet": MessageLookupByLibrary.simpleMessage("Quiet Area"),
    "amenityWifi": MessageLookupByLibrary.simpleMessage("Wi-Fi"),
    "appDescription": MessageLookupByLibrary.simpleMessage(
      "Book your ideal workspace with ease.",
    ),
    "appName": MessageLookupByLibrary.simpleMessage("Anis"),
    "appTagline": MessageLookupByLibrary.simpleMessage(
      "Study smarter together",
    ),
    "asr": MessageLookupByLibrary.simpleMessage("Asr"),
    "attendanceStatistics": MessageLookupByLibrary.simpleMessage(
      "Attendance Statistics",
    ),
    "authenticationError": MessageLookupByLibrary.simpleMessage(
      "User name or password is incorrect",
    ),
    "availableNow": MessageLookupByLibrary.simpleMessage("Available Now"),
    "awaitingCheckoutApproval": MessageLookupByLibrary.simpleMessage(
      "Awaiting approval to leave",
    ),
    "badgesEarned": MessageLookupByLibrary.simpleMessage("Badges Earned"),
    "beFirstToStartSession": MessageLookupByLibrary.simpleMessage(
      "Be the first to start a session here!",
    ),
    "buddiesTab": MessageLookupByLibrary.simpleMessage("Buddies"),
    "buddyScreenSubtitle": MessageLookupByLibrary.simpleMessage(
      "Find the right study partner for you",
    ),
    "byLoggingInYouAgree": MessageLookupByLibrary.simpleMessage(
      "By logging in you agree to our",
    ),
    "callUs": MessageLookupByLibrary.simpleMessage("Call us"),
    "camera": MessageLookupByLibrary.simpleMessage("Camera"),
    "cancel": MessageLookupByLibrary.simpleMessage("Cancel"),
    "category": MessageLookupByLibrary.simpleMessage("Category"),
    "changeLanguage": MessageLookupByLibrary.simpleMessage("Change Language"),
    "checkIn": MessageLookupByLibrary.simpleMessage("Check In"),
    "checkInSuccess": MessageLookupByLibrary.simpleMessage(
      "Checked in successfully!",
    ),
    "checkOut": MessageLookupByLibrary.simpleMessage("Check Out"),
    "checkOutSuccess": MessageLookupByLibrary.simpleMessage(
      "Session saved. See you next time!",
    ),
    "checkedIn": MessageLookupByLibrary.simpleMessage("checked In"),
    "checkedOut": MessageLookupByLibrary.simpleMessage("checked Out"),
    "checkingIn": MessageLookupByLibrary.simpleMessage("Checking in..."),
    "checkingOut": MessageLookupByLibrary.simpleMessage("Saving session..."),
    "checkoutPendingSubtitle": MessageLookupByLibrary.simpleMessage(
      "The workspace will approve your checkout shortly.",
    ),
    "checkoutRequestSent": MessageLookupByLibrary.simpleMessage(
      "Checkout request sent",
    ),
    "choosePlanTitle": MessageLookupByLibrary.simpleMessage("Choose Your Plan"),
    "chooseYourGender": MessageLookupByLibrary.simpleMessage(
      "Choose Your Gender",
    ),
    "closedNow": MessageLookupByLibrary.simpleMessage("Closed"),
    "color": MessageLookupByLibrary.simpleMessage("Color"),
    "completeProfileSubtitle": MessageLookupByLibrary.simpleMessage(
      "Tell us a little about your studies so we can recommend better sessions and study buddies.",
    ),
    "completeProfileTitle": MessageLookupByLibrary.simpleMessage(
      "Make Anis feel like yours",
    ),
    "confirmPassword": MessageLookupByLibrary.simpleMessage("Confirm Password"),
    "continueAsGuest": MessageLookupByLibrary.simpleMessage(
      "Continue as Guest",
    ),
    "continuee": MessageLookupByLibrary.simpleMessage("continue"),
    "createAccount": MessageLookupByLibrary.simpleMessage("Create account"),
    "createSession": MessageLookupByLibrary.simpleMessage("Create Session"),
    "createSessionAddRule": MessageLookupByLibrary.simpleMessage("Add Rule"),
    "createSessionBasicInfo": MessageLookupByLibrary.simpleMessage(
      "Basic Info",
    ),
    "createSessionCapacity": MessageLookupByLibrary.simpleMessage(
      "Max Members",
    ),
    "createSessionDescHint": MessageLookupByLibrary.simpleMessage(
      "What will you be working on?",
    ),
    "createSessionDescription": MessageLookupByLibrary.simpleMessage(
      "Description",
    ),
    "createSessionGiftHint": MessageLookupByLibrary.simpleMessage(
      "e.g. Coffee, snacks...",
    ),
    "createSessionGiftLabel": MessageLookupByLibrary.simpleMessage(
      "Gift / Treat",
    ),
    "createSessionPersons": MessageLookupByLibrary.simpleMessage("persons"),
    "createSessionRuleHint": MessageLookupByLibrary.simpleMessage(
      "e.g. No talking loudly",
    ),
    "createSessionSubject": MessageLookupByLibrary.simpleMessage("Subject"),
    "createSessionSubjectHint": MessageLookupByLibrary.simpleMessage(
      "e.g. Mathematics, Physics...",
    ),
    "createSessionSubmit": MessageLookupByLibrary.simpleMessage(
      "Create Session",
    ),
    "createSessionTitle": MessageLookupByLibrary.simpleMessage(
      "Create a Study Session",
    ),
    "createSessionTopic": MessageLookupByLibrary.simpleMessage("Topic"),
    "createSessionTopicHint": MessageLookupByLibrary.simpleMessage(
      "e.g. Calculus Chapter 3",
    ),
    "createYourAccount": MessageLookupByLibrary.simpleMessage(
      "Create your account",
    ),
    "currentPlan": MessageLookupByLibrary.simpleMessage("Current Plan"),
    "currentSettings": MessageLookupByLibrary.simpleMessage("Current Settings"),
    "currentWorkspace": MessageLookupByLibrary.simpleMessage(
      "Current Workspace",
    ),
    "customMultiplierLabel": MessageLookupByLibrary.simpleMessage("Custom"),
    "date": MessageLookupByLibrary.simpleMessage("date"),
    "dayCalculationHoursLabel": MessageLookupByLibrary.simpleMessage(
      "Study Day Hours",
    ),
    "dayHoursExplain": MessageLookupByLibrary.simpleMessage(
      "Number of attendance hours that equals one full subscription day.",
    ),
    "daysLeft": m0,
    "des": MessageLookupByLibrary.simpleMessage("Description"),
    "description": MessageLookupByLibrary.simpleMessage("Description"),
    "dhuhr": MessageLookupByLibrary.simpleMessage("Dhuhr"),
    "doItLater": MessageLookupByLibrary.simpleMessage("Do it later"),
    "done": MessageLookupByLibrary.simpleMessage("Done"),
    "drinksMenu": MessageLookupByLibrary.simpleMessage("Drinks Menu"),
    "editSettings": MessageLookupByLibrary.simpleMessage("Edit Settings"),
    "email": MessageLookupByLibrary.simpleMessage("Email"),
    "enterActivationCodeHint": MessageLookupByLibrary.simpleMessage(
      "Enter the code you received",
    ),
    "enterEmailToResetPassword": MessageLookupByLibrary.simpleMessage(
      "Enter Your E-mail to Reset Your Password",
    ),
    "enterOtp": MessageLookupByLibrary.simpleMessage(
      "Enter The Verification Code which sent on Your E-mail",
    ),
    "error": MessageLookupByLibrary.simpleMessage("there is an error"),
    "excellent": MessageLookupByLibrary.simpleMessage("Excellent"),
    "facebookPage": MessageLookupByLibrary.simpleMessage("Facebook page"),
    "fajr": MessageLookupByLibrary.simpleMessage("Fajr"),
    "feed": MessageLookupByLibrary.simpleMessage("Feedback"),
    "female": MessageLookupByLibrary.simpleMessage("Female"),
    "fieldRequired": MessageLookupByLibrary.simpleMessage(
      "This field is required",
    ),
    "filterToday": MessageLookupByLibrary.simpleMessage("Today"),
    "findBuddy": MessageLookupByLibrary.simpleMessage("Find Buddy"),
    "freeSubscription": MessageLookupByLibrary.simpleMessage("Free Plan"),
    "freeWorkspaceLabel": MessageLookupByLibrary.simpleMessage("Free  (0×)"),
    "full": MessageLookupByLibrary.simpleMessage("Full Name"),
    "fullNameHint": MessageLookupByLibrary.simpleMessage("Your full name"),
    "gallery": MessageLookupByLibrary.simpleMessage("Gallery"),
    "gender": MessageLookupByLibrary.simpleMessage("Gender"),
    "generalError": MessageLookupByLibrary.simpleMessage(
      "Something went wrong. Please try again.",
    ),
    "getStarted": MessageLookupByLibrary.simpleMessage("Get Started"),
    "goldSubscription": MessageLookupByLibrary.simpleMessage("Gold Plan"),
    "good": MessageLookupByLibrary.simpleMessage("Good"),
    "goodAfternoon": MessageLookupByLibrary.simpleMessage("Good afternoon"),
    "goodEvening": MessageLookupByLibrary.simpleMessage("Good evening"),
    "goodMorning": MessageLookupByLibrary.simpleMessage("Good morning"),
    "groups": MessageLookupByLibrary.simpleMessage("Groups"),
    "guestRestrictedBody": MessageLookupByLibrary.simpleMessage(
      "This feature is available to registered students. Join Anis for free to unlock study sessions, buddy matching, and premium workspaces.",
    ),
    "guestRestrictedTitle": MessageLookupByLibrary.simpleMessage(
      "Members Only",
    ),
    "hajj": MessageLookupByLibrary.simpleMessage("Hajj Activity"),
    "haveActivationCode": MessageLookupByLibrary.simpleMessage(
      "I have an activation code",
    ),
    "home": MessageLookupByLibrary.simpleMessage("Home"),
    "homeTab": MessageLookupByLibrary.simpleMessage("Home"),
    "hour": MessageLookupByLibrary.simpleMessage("hour"),
    "hourMultiplierExplain": MessageLookupByLibrary.simpleMessage(
      "Each real attendance hour is counted at this multiplier against the student\'s subscription balance.",
    ),
    "hourMultiplierLabel": MessageLookupByLibrary.simpleMessage(
      "Hour Multiplier",
    ),
    "hours": MessageLookupByLibrary.simpleMessage("Hours"),
    "hoursStudiedToday": MessageLookupByLibrary.simpleMessage(
      "Study Hours Today",
    ),
    "inProgress": MessageLookupByLibrary.simpleMessage("In Progress"),
    "infoTab": MessageLookupByLibrary.simpleMessage("Info"),
    "interestEngineering": MessageLookupByLibrary.simpleMessage("Engineering"),
    "interestHint": MessageLookupByLibrary.simpleMessage("For example: Design"),
    "interestLanguages": MessageLookupByLibrary.simpleMessage("Languages"),
    "interestMathematics": MessageLookupByLibrary.simpleMessage("Mathematics"),
    "interestMedicine": MessageLookupByLibrary.simpleMessage("Medicine"),
    "interestProgramming": MessageLookupByLibrary.simpleMessage("Programming"),
    "interests": MessageLookupByLibrary.simpleMessage("Study interests"),
    "invalidEmail": MessageLookupByLibrary.simpleMessage(
      " please enter valid email ",
    ),
    "invalidWorkspaceQr": MessageLookupByLibrary.simpleMessage(
      "This QR code is not a valid Anis workspace.",
    ),
    "isha": MessageLookupByLibrary.simpleMessage("Isha"),
    "joinSession": MessageLookupByLibrary.simpleMessage("Join"),
    "juz": MessageLookupByLibrary.simpleMessage("Juz"),
    "kaaba": MessageLookupByLibrary.simpleMessage("Kaaba Tawaf"),
    "km": MessageLookupByLibrary.simpleMessage("km"),
    "languageArabic": MessageLookupByLibrary.simpleMessage("العربية"),
    "languageEnglish": MessageLookupByLibrary.simpleMessage("English"),
    "languageTurkish": MessageLookupByLibrary.simpleMessage("Turkish"),
    "lastSeen": MessageLookupByLibrary.simpleMessage("Last seen"),
    "later": MessageLookupByLibrary.simpleMessage("Later"),
    "leaveWorkspace": MessageLookupByLibrary.simpleMessage(
      "Logout from Workspace",
    ),
    "liveNow": MessageLookupByLibrary.simpleMessage("Live Now"),
    "loading": MessageLookupByLibrary.simpleMessage("Loading..."),
    "loc": MessageLookupByLibrary.simpleMessage(
      "Location service Denied Forever !",
    ),
    "locSer": MessageLookupByLibrary.simpleMessage(
      "Location service permission denied",
    ),
    "locateViaGps": MessageLookupByLibrary.simpleMessage("Locate via GPS"),
    "locateYourself": MessageLookupByLibrary.simpleMessage("Locate yourself"),
    "locateYourselfDesc": MessageLookupByLibrary.simpleMessage(
      "Choose your location so we can show you the nearest workspaces and accurately calculate distances",
    ),
    "locating": MessageLookupByLibrary.simpleMessage("Locating..."),
    "location": MessageLookupByLibrary.simpleMessage("Location"),
    "locationError": MessageLookupByLibrary.simpleMessage("Location Error"),
    "logOut": MessageLookupByLibrary.simpleMessage("Log Out"),
    "login": MessageLookupByLibrary.simpleMessage("Login"),
    "loginOrRegister": MessageLookupByLibrary.simpleMessage("Login / Register"),
    "logout": MessageLookupByLibrary.simpleMessage("Logout"),
    "logoutQuestion": MessageLookupByLibrary.simpleMessage(
      "Are you sure you want to logout?",
    ),
    "maghrib": MessageLookupByLibrary.simpleMessage("Maghrib"),
    "male": MessageLookupByLibrary.simpleMessage("Male"),
    "manageSubscription": MessageLookupByLibrary.simpleMessage(
      "Manage Subscription",
    ),
    "memberInterests": MessageLookupByLibrary.simpleMessage("Interests"),
    "memberRating": MessageLookupByLibrary.simpleMessage("Rating"),
    "myWorkspace": MessageLookupByLibrary.simpleMessage("My Workspace"),
    "name": MessageLookupByLibrary.simpleMessage("Name:"),
    "nameTooShortError": MessageLookupByLibrary.simpleMessage(
      "Name must be at least 2 characters",
    ),
    "nameWillAppearOnProfile": MessageLookupByLibrary.simpleMessage(
      "Your name appears on your profile and study sessions.",
    ),
    "nearbyFilter": MessageLookupByLibrary.simpleMessage("Nearby"),
    "next": MessageLookupByLibrary.simpleMessage("Next"),
    "noBuddiesFound": MessageLookupByLibrary.simpleMessage("No buddies found"),
    "noData": MessageLookupByLibrary.simpleMessage(
      "There is no data here till now",
    ),
    "noRating": MessageLookupByLibrary.simpleMessage("No Rating"),
    "noSessionsInWorkspace": MessageLookupByLibrary.simpleMessage(
      "No active sessions in this workspace",
    ),
    "noSessionsToday": MessageLookupByLibrary.simpleMessage(
      "No sessions today",
    ),
    "noWorkspacesFound": MessageLookupByLibrary.simpleMessage(
      "No workspaces found",
    ),
    "notification": MessageLookupByLibrary.simpleMessage("Notifications"),
    "onboarding1Desc": MessageLookupByLibrary.simpleMessage(
      "With a single subscription, walk into any nearby workspace and study in a focused, distraction-free environment — no extra costs, ever.",
    ),
    "onboarding1Title": MessageLookupByLibrary.simpleMessage(
      "One Pass. Every Space.",
    ),
    "onboarding2Desc": MessageLookupByLibrary.simpleMessage(
      "Discover available sessions at workspaces near you. Join peers studying the same subject and make every hour count.",
    ),
    "onboarding2Title": MessageLookupByLibrary.simpleMessage(
      "Join Live Study Sessions",
    ),
    "onboarding3Desc": MessageLookupByLibrary.simpleMessage(
      "Search for a study buddy who matches your pace, or join a group — study together, learn something new, grow as a team.",
    ),
    "onboarding3Title": MessageLookupByLibrary.simpleMessage("Find Your Anis"),
    "onlineNow": MessageLookupByLibrary.simpleMessage("Online Now"),
    "open": MessageLookupByLibrary.simpleMessage("Open Google Map"),
    "openInMaps": MessageLookupByLibrary.simpleMessage("Open in Maps"),
    "openNow": MessageLookupByLibrary.simpleMessage("Open Now"),
    "openSpot": MessageLookupByLibrary.simpleMessage("Open spot"),
    "or": MessageLookupByLibrary.simpleMessage("OR"),
    "orChooseRegionManually": MessageLookupByLibrary.simpleMessage(
      "Or choose a region manually",
    ),
    "orderNumber": MessageLookupByLibrary.simpleMessage("Order Number "),
    "otp": MessageLookupByLibrary.simpleMessage("OTP"),
    "ownerPanelTitle": MessageLookupByLibrary.simpleMessage("Owner Panel"),
    "password": MessageLookupByLibrary.simpleMessage("Password"),
    "passwordEmptyError": MessageLookupByLibrary.simpleMessage(
      "Password is required",
    ),
    "passwordHint": MessageLookupByLibrary.simpleMessage("••••••••"),
    "passwordMissingNumberError": MessageLookupByLibrary.simpleMessage(
      "Password must include a number",
    ),
    "passwordsDoNotMatch": MessageLookupByLibrary.simpleMessage(
      "Passwords do not match",
    ),
    "phoneHint": MessageLookupByLibrary.simpleMessage("+20 1XX XXX XXXX"),
    "phoneNumber": MessageLookupByLibrary.simpleMessage("Phone Number"),
    "phoneRequired": MessageLookupByLibrary.simpleMessage(
      "Phone number is required",
    ),
    "planActivatedSuccessfully": MessageLookupByLibrary.simpleMessage(
      "Plan activated successfully",
    ),
    "planBestValue": MessageLookupByLibrary.simpleMessage("Best Value"),
    "planCtaFree": MessageLookupByLibrary.simpleMessage("Get Started Free"),
    "planCtaGold": MessageLookupByLibrary.simpleMessage("Go Gold"),
    "planCtaSilver": MessageLookupByLibrary.simpleMessage("Go Silver"),
    "planCurrentBadge": MessageLookupByLibrary.simpleMessage("Current"),
    "planFeatureAds": MessageLookupByLibrary.simpleMessage("Contains ads"),
    "planFeatureAnalytics": MessageLookupByLibrary.simpleMessage(
      "Study analytics",
    ),
    "planFeatureBuddies": MessageLookupByLibrary.simpleMessage(
      "Up to 3 buddies",
    ),
    "planFeatureGoldHours": MessageLookupByLibrary.simpleMessage(
      "200 hours/month",
    ),
    "planFeatureNoAds": MessageLookupByLibrary.simpleMessage("No ads"),
    "planFeaturePriority": MessageLookupByLibrary.simpleMessage(
      "Priority support",
    ),
    "planFeatureSessions2": MessageLookupByLibrary.simpleMessage(
      "Up to 2 sessions/month",
    ),
    "planFeatureSilverHours": MessageLookupByLibrary.simpleMessage(
      "120 hours/month",
    ),
    "planFeatureWorkspaces": MessageLookupByLibrary.simpleMessage(
      "Limited workspaces",
    ),
    "planFeatureWorkspacesUnlimited": MessageLookupByLibrary.simpleMessage(
      "All workspaces",
    ),
    "planFreeTitle": MessageLookupByLibrary.simpleMessage("Free"),
    "planGoldTitle": MessageLookupByLibrary.simpleMessage("Gold"),
    "planMostPopular": MessageLookupByLibrary.simpleMessage("Most Popular"),
    "planPriceFree": MessageLookupByLibrary.simpleMessage("0 EGP"),
    "planPriceGold": MessageLookupByLibrary.simpleMessage("2,300 EGP/mo"),
    "planPriceSilver": MessageLookupByLibrary.simpleMessage("1,700 EGP/mo"),
    "planSilverTitle": MessageLookupByLibrary.simpleMessage("Silver"),
    "please": MessageLookupByLibrary.simpleMessage(
      "Please enable Location service",
    ),
    "pleaseEndterValue": MessageLookupByLibrary.simpleMessage(
      "please dont let this filed null",
    ),
    "poor": MessageLookupByLibrary.simpleMessage("Poor"),
    "premiumWorkspaceLabel": MessageLookupByLibrary.simpleMessage(
      "Premium  (2×)",
    ),
    "previewNote": m1,
    "price": MessageLookupByLibrary.simpleMessage("Price"),
    "privacyPolicy": MessageLookupByLibrary.simpleMessage("Privacy Policy"),
    "profile": MessageLookupByLibrary.simpleMessage("Profile"),
    "profileEmailHint": MessageLookupByLibrary.simpleMessage(
      "you@university.edu.eg",
    ),
    "profileGenderReason": MessageLookupByLibrary.simpleMessage(
      "Helps personalize your profile and recommendations.",
    ),
    "profileInterestsReason": MessageLookupByLibrary.simpleMessage(
      "Choose topics you would enjoy studying with others.",
    ),
    "profileTab": MessageLookupByLibrary.simpleMessage("Profile"),
    "profileUniversityHint": MessageLookupByLibrary.simpleMessage(
      "Your university or institute",
    ),
    "purchasePlanManualDescription": MessageLookupByLibrary.simpleMessage(
      "Contact us and pay in cash. After receiving payment, we will send your activation code. Bank payment is coming later.",
    ),
    "purchasePlanManually": MessageLookupByLibrary.simpleMessage(
      "Purchase the plan manually",
    ),
    "purchasePlanMessage": m2,
    "regionDokki": MessageLookupByLibrary.simpleMessage("Dokki, Giza"),
    "regionFifthSettlement": MessageLookupByLibrary.simpleMessage(
      "5th Settlement, Cairo",
    ),
    "regionNasrCity": MessageLookupByLibrary.simpleMessage("Nasr City, Cairo"),
    "regionOctober": MessageLookupByLibrary.simpleMessage(
      "6th of October, Giza",
    ),
    "regionSmouha": MessageLookupByLibrary.simpleMessage("Smouha, Alexandria"),
    "register": MessageLookupByLibrary.simpleMessage("Register"),
    "requestToLeave": MessageLookupByLibrary.simpleMessage("Request to leave"),
    "resetPassword": MessageLookupByLibrary.simpleMessage("Reset Password"),
    "resultsCount": m3,
    "retry": MessageLookupByLibrary.simpleMessage("Retry"),
    "round": MessageLookupByLibrary.simpleMessage("Arrival Time"),
    "saveAndContinue": MessageLookupByLibrary.simpleMessage(
      "Save and continue",
    ),
    "saveSettings": MessageLookupByLibrary.simpleMessage("Save Settings"),
    "savingSettings": MessageLookupByLibrary.simpleMessage("Saving..."),
    "scanQrShort": MessageLookupByLibrary.simpleMessage("Scan QR"),
    "scanQrToCheckIn": MessageLookupByLibrary.simpleMessage(
      "Scan workspace QR to check in",
    ),
    "scanToCheckIn": MessageLookupByLibrary.simpleMessage(
      "Scan the code to check in",
    ),
    "searchWorkspace": MessageLookupByLibrary.simpleMessage(
      "Search workspaces...",
    ),
    "selectGenderError": MessageLookupByLibrary.simpleMessage(
      "Please select your gender",
    ),
    "selectLanguage": MessageLookupByLibrary.simpleMessage("Select Language"),
    "selectWorkspace": MessageLookupByLibrary.simpleMessage("Select Workspace"),
    "selectWorkspaceHint": MessageLookupByLibrary.simpleMessage(
      "Tap to choose a workspace",
    ),
    "send": MessageLookupByLibrary.simpleMessage("Send"),
    "sendOtp": MessageLookupByLibrary.simpleMessage("verify OTP"),
    "sending": MessageLookupByLibrary.simpleMessage("Sending ....."),
    "sendingCheckoutRequest": MessageLookupByLibrary.simpleMessage(
      "Sending request...",
    ),
    "services": MessageLookupByLibrary.simpleMessage("services"),
    "sessionDate": MessageLookupByLibrary.simpleMessage("Date"),
    "sessionDescription": MessageLookupByLibrary.simpleMessage(
      "About this session",
    ),
    "sessionFounder": MessageLookupByLibrary.simpleMessage("Founder"),
    "sessionFull": MessageLookupByLibrary.simpleMessage("Session Full"),
    "sessionGift": MessageLookupByLibrary.simpleMessage("Gift"),
    "sessionInProgress": MessageLookupByLibrary.simpleMessage("In Progress"),
    "sessionJoined": MessageLookupByLibrary.simpleMessage("Joined"),
    "sessionMembers": MessageLookupByLibrary.simpleMessage("Members"),
    "sessionOpen": MessageLookupByLibrary.simpleMessage("Open"),
    "sessionPlace": MessageLookupByLibrary.simpleMessage("Location"),
    "sessionRules": MessageLookupByLibrary.simpleMessage("Session Rules"),
    "sessionTabOverview": MessageLookupByLibrary.simpleMessage("Overview"),
    "sessionTabPeople": MessageLookupByLibrary.simpleMessage("People"),
    "sessionTime": MessageLookupByLibrary.simpleMessage("Time"),
    "sessionsCount": MessageLookupByLibrary.simpleMessage("Sessions"),
    "sessionsTab": MessageLookupByLibrary.simpleMessage("Sessions"),
    "settings": MessageLookupByLibrary.simpleMessage("Settings"),
    "settingsSaved": MessageLookupByLibrary.simpleMessage(
      "Settings saved successfully",
    ),
    "show": MessageLookupByLibrary.simpleMessage("Show All"),
    "signIn": MessageLookupByLibrary.simpleMessage("Sign in"),
    "signInToSeeProfile": MessageLookupByLibrary.simpleMessage(
      "Sign in to build your study profile",
    ),
    "signInToSeeProfileSubtitle": MessageLookupByLibrary.simpleMessage(
      "Your profile, progress, badges, and subscription details are available after signing in.",
    ),
    "silverSubscription": MessageLookupByLibrary.simpleMessage("Silver Plan"),
    "size": MessageLookupByLibrary.simpleMessage("Size"),
    "skip": MessageLookupByLibrary.simpleMessage("SKIP INTRO"),
    "specialization": MessageLookupByLibrary.simpleMessage(
      "Specialization / Major",
    ),
    "specializationHint": MessageLookupByLibrary.simpleMessage(
      "e.g. Computer Science, Medicine...",
    ),
    "splashArabicHint": MessageLookupByLibrary.simpleMessage("Arabic"),
    "splashChooseLanguage": MessageLookupByLibrary.simpleMessage(
      "Choose your language",
    ),
    "splashEnglishHint": MessageLookupByLibrary.simpleMessage("English"),
    "splashScreenText": MessageLookupByLibrary.simpleMessage(
      "One subscription unlocks any nearby workspace, live study sessions, and the right study partner.",
    ),
    "splashTagline": MessageLookupByLibrary.simpleMessage(
      "Workspace  ·  Session  ·  Buddy",
    ),
    "splashTitle": MessageLookupByLibrary.simpleMessage("Anis"),
    "standardWorkspaceLabel": MessageLookupByLibrary.simpleMessage(
      "Standard  (1×)",
    ),
    "startNow": MessageLookupByLibrary.simpleMessage("Start Now"),
    "status": MessageLookupByLibrary.simpleMessage("Status"),
    "stepIndicator": m4,
    "streakDays": MessageLookupByLibrary.simpleMessage("Day Streak"),
    "strongPasswordHint": MessageLookupByLibrary.simpleMessage(
      "Use at least 8 characters with uppercase, lowercase, and a number.",
    ),
    "studyBuddy": MessageLookupByLibrary.simpleMessage("Study Buddy"),
    "studyTimeLabel": MessageLookupByLibrary.simpleMessage("Study Time"),
    "subjectHint": MessageLookupByLibrary.simpleMessage("Search by subject..."),
    "subscribe": MessageLookupByLibrary.simpleMessage("Subscribe Now"),
    "subscriptionDaysAndHours": m5,
    "subscriptionDaysLeft": MessageLookupByLibrary.simpleMessage(
      "Subscription days left",
    ),
    "subscriptionDaysProgressValue": m6,
    "subscriptionDaysUsed": m7,
    "subscriptionProgress": MessageLookupByLibrary.simpleMessage(
      "Subscription",
    ),
    "subscriptionTotalDaysLabel": m8,
    "success": MessageLookupByLibrary.simpleMessage("Success"),
    "sunrise": MessageLookupByLibrary.simpleMessage("Sunrise"),
    "thankFeed": MessageLookupByLibrary.simpleMessage(
      "Thanks for sending feedback.",
    ),
    "thisWeek": MessageLookupByLibrary.simpleMessage("This Week"),
    "today": MessageLookupByLibrary.simpleMessage("Today Notifications"),
    "todaysSessions": MessageLookupByLibrary.simpleMessage("Today\'s Sessions"),
    "totalEarlyDepartureHours": MessageLookupByLibrary.simpleMessage(
      "Total Early Departure Hours",
    ),
    "totalLateHours": MessageLookupByLibrary.simpleMessage("Total Late Hours"),
    "totalOvertimeHours": MessageLookupByLibrary.simpleMessage(
      "Total Overtime Hours",
    ),
    "totalStudyHours": MessageLookupByLibrary.simpleMessage(
      "Total Study Hours",
    ),
    "totalWorkHours": MessageLookupByLibrary.simpleMessage("Total Work Hours"),
    "tryDifferentFilters": MessageLookupByLibrary.simpleMessage(
      "Try different filters",
    ),
    "tryLater": MessageLookupByLibrary.simpleMessage("Please Try Later"),
    "type": MessageLookupByLibrary.simpleMessage("Type:"),
    "university": MessageLookupByLibrary.simpleMessage("University"),
    "universityHint": MessageLookupByLibrary.simpleMessage(
      "Search by university...",
    ),
    "unknown": MessageLookupByLibrary.simpleMessage("Unknown"),
    "upcoming": MessageLookupByLibrary.simpleMessage("Upcoming"),
    "upcomingSessions": MessageLookupByLibrary.simpleMessage(
      "Upcoming Sessions",
    ),
    "update": MessageLookupByLibrary.simpleMessage("Update"),
    "updateAvailable": MessageLookupByLibrary.simpleMessage("Update Available"),
    "updateBody": MessageLookupByLibrary.simpleMessage(
      "A new version of the TEAA is available. Please update to continue",
    ),
    "upgradePlan": MessageLookupByLibrary.simpleMessage("Upgrade Plan"),
    "userName": MessageLookupByLibrary.simpleMessage("User Name"),
    "veryGood": MessageLookupByLibrary.simpleMessage("Very Good"),
    "viewAll": MessageLookupByLibrary.simpleMessage("View all"),
    "waiting": MessageLookupByLibrary.simpleMessage("Waiting"),
    "welcome": MessageLookupByLibrary.simpleMessage("Welcome Back,"),
    "whatsAppNumber": MessageLookupByLibrary.simpleMessage("WhatsApp Number"),
    "whatsAppNumberHint": MessageLookupByLibrary.simpleMessage(
      "+20 1XX XXX XXXX",
    ),
    "whatsAppNumberRequired": MessageLookupByLibrary.simpleMessage(
      "WhatsApp number is required",
    ),
    "whatsappNumber": m9,
    "workspaceBusy": MessageLookupByLibrary.simpleMessage("Busy"),
    "workspaceCapacity": MessageLookupByLibrary.simpleMessage("Capacity"),
    "workspaceDailyCapText": m10,
    "workspaceDayCalculationTitle": MessageLookupByLibrary.simpleMessage(
      "How this workspace counts a day",
    ),
    "workspaceFull": MessageLookupByLibrary.simpleMessage("Full"),
    "workspaceHourMultiplierCustom": m11,
    "workspaceHourMultiplierFree": MessageLookupByLibrary.simpleMessage(
      "Free workspace (1 real hour = 0 subscription hours)",
    ),
    "workspaceHourMultiplierPremium": MessageLookupByLibrary.simpleMessage(
      "Premium workspace (1 real hour = 2 subscription hours)",
    ),
    "workspaceHourMultiplierStandard": MessageLookupByLibrary.simpleMessage(
      "Standard workspace (1 real hour = 1 subscription hour)",
    ),
    "workspaceOpen": MessageLookupByLibrary.simpleMessage("Available"),
    "workspaceSettingsTitle": MessageLookupByLibrary.simpleMessage(
      "Workspace Settings",
    ),
    "workspacesSubtitle": MessageLookupByLibrary.simpleMessage(
      "Find the perfect spot to study with your buddy",
    ),
    "workspacesTab": MessageLookupByLibrary.simpleMessage("Workspaces"),
    "yourAccount": MessageLookupByLibrary.simpleMessage("Your Account"),
  };
}

// DO NOT EDIT. This is code generated via package:intl/generate_localized.dart
// This is a library that provides messages for a ar locale. All the
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
  String get localeName => 'ar';

  static String m0(days) => "متبقي ${days} يوم";

  static String m1(val, hours) =>
      "كل ساعة حضور = ${val} ساعة اشتراك | يوم = ${hours} ساعات";

  static String m2(planName) =>
      "مرحباً، أريد شراء باقة ${planName} في تطبيق أنيس.";

  static String m3(count) => "${count} نتيجة";

  static String m4(step, total, label) =>
      "الخطوة ${step} من ${total} • ${label}";

  static String m5(days, hours) => "متبقي ${days} يوم • ${hours} ساعة";

  static String m6(remaining, total) => "${remaining}/${total} يوم";

  static String m7(days) => "${days} يوم مستخدم";

  static String m8(days) => "${days} يوم";

  static String m9(phone) => "واتساب ${phone}";

  static String m10(capHours, realHours) =>
      "الحد الأقصى للخصم اليومي: ${capHours} ساعة اشتراك (ما يعادل ${realHours} ساعات حضور حقيقية)";

  static String m11(multiplier) => "ساعة الحضور = ${multiplier} ساعة اشتراك";

  final messages = _notInlinedMessages(_notInlinedMessages);
  static Map<String, Function> _notInlinedMessages(_) => <String, Function>{
    "ac": MessageLookupByLibrary.simpleMessage("مخيمات عرفة"),
    "activate": MessageLookupByLibrary.simpleMessage("تفعيل"),
    "activateCode": MessageLookupByLibrary.simpleMessage("تفعيل الكود"),
    "activatePlanCode": MessageLookupByLibrary.simpleMessage(
      "تفعيل كود الباقة",
    ),
    "activationBannerDesc": MessageLookupByLibrary.simpleMessage(
      "استلمت كود الباقة؟ فعّله هنا.",
    ),
    "activities": MessageLookupByLibrary.simpleMessage("الأنشطة"),
    "add": MessageLookupByLibrary.simpleMessage("إضافة"),
    "addAnotherInterest": MessageLookupByLibrary.simpleMessage(
      "أضف اهتمامًا آخر",
    ),
    "addInterestError": MessageLookupByLibrary.simpleMessage(
      "اختر اهتمامًا واحدًا على الأقل",
    ),
    "address": MessageLookupByLibrary.simpleMessage("عنــوان الشحــن"),
    "adminPanelTitle": MessageLookupByLibrary.simpleMessage("لوحة الإدارة"),
    "allFilter": MessageLookupByLibrary.simpleMessage("الكل"),
    "alreadyHaveAccount": MessageLookupByLibrary.simpleMessage(
      "لديك حساب بالفعل؟",
    ),
    "amenities": MessageLookupByLibrary.simpleMessage("المرافق"),
    "amenityAc": MessageLookupByLibrary.simpleMessage("تكييف"),
    "amenityCoffee": MessageLookupByLibrary.simpleMessage("قهوة"),
    "amenityPrinting": MessageLookupByLibrary.simpleMessage("طباعة"),
    "amenityQuiet": MessageLookupByLibrary.simpleMessage("هادئ"),
    "amenityWifi": MessageLookupByLibrary.simpleMessage("واي فاي"),
    "appDescription": MessageLookupByLibrary.simpleMessage(
      "احجز مساحة العمل المثالية بكل سهولة.",
    ),
    "appName": MessageLookupByLibrary.simpleMessage("أنيس"),
    "appTagline": MessageLookupByLibrary.simpleMessage("ذاكر بذكاء مع رفيقك"),
    "asr": MessageLookupByLibrary.simpleMessage("العصر"),
    "attendanceStatistics": MessageLookupByLibrary.simpleMessage(
      "إحصائيات الحضور",
    ),
    "authenticationError": MessageLookupByLibrary.simpleMessage(
      "اسم المستخدم او كلمه السر غير صحيحه",
    ),
    "availableNow": MessageLookupByLibrary.simpleMessage("متاح الآن"),
    "awaitingCheckoutApproval": MessageLookupByLibrary.simpleMessage(
      "بانتظار الموافقة على الخروج",
    ),
    "badgesEarned": MessageLookupByLibrary.simpleMessage("الشارات المكتسبة"),
    "beFirstToStartSession": MessageLookupByLibrary.simpleMessage(
      "كن أول من يبدأ جلسة هنا!",
    ),
    "buddiesTab": MessageLookupByLibrary.simpleMessage("رفاق"),
    "buddyScreenSubtitle": MessageLookupByLibrary.simpleMessage(
      "ابحث عن شريك دراسة يناسبك",
    ),
    "byLoggingInYouAgree": MessageLookupByLibrary.simpleMessage(
      "بتسجيل دخولك أنت توافق على",
    ),
    "callUs": MessageLookupByLibrary.simpleMessage("اتصل بنا"),
    "camera": MessageLookupByLibrary.simpleMessage("كاميرا"),
    "cancel": MessageLookupByLibrary.simpleMessage("إلغاء"),
    "category": MessageLookupByLibrary.simpleMessage("القسم"),
    "changeLanguage": MessageLookupByLibrary.simpleMessage("تغيير اللغة"),
    "checkIn": MessageLookupByLibrary.simpleMessage("تسجيل الوصول"),
    "checkInSuccess": MessageLookupByLibrary.simpleMessage(
      "تم تسجيل الدخول بنجاح!",
    ),
    "checkOut": MessageLookupByLibrary.simpleMessage("تسجيل الخروج"),
    "checkOutSuccess": MessageLookupByLibrary.simpleMessage(
      "تم حفظ الجلسة. إلى اللقاء!",
    ),
    "checkedIn": MessageLookupByLibrary.simpleMessage("تم تسجيل الوصول"),
    "checkedOut": MessageLookupByLibrary.simpleMessage("تم تسجيل الخروج"),
    "checkingIn": MessageLookupByLibrary.simpleMessage("جارٍ تسجيل الدخول..."),
    "checkingOut": MessageLookupByLibrary.simpleMessage("جارٍ حفظ الجلسة..."),
    "checkoutPendingSubtitle": MessageLookupByLibrary.simpleMessage(
      "سيوافق المكان على تسجيل خروجك قريباً.",
    ),
    "checkoutRequestSent": MessageLookupByLibrary.simpleMessage(
      "تم إرسال طلب الخروج",
    ),
    "choosePlanTitle": MessageLookupByLibrary.simpleMessage("اختر خطتك"),
    "chooseYourGender": MessageLookupByLibrary.simpleMessage("اختر جنسك"),
    "closedNow": MessageLookupByLibrary.simpleMessage("مغلق الآن"),
    "color": MessageLookupByLibrary.simpleMessage("اللون"),
    "completeProfileSubtitle": MessageLookupByLibrary.simpleMessage(
      "أخبرنا قليلًا عن دراستك لنرشح لك جلسات ورفقاء دراسة أفضل.",
    ),
    "completeProfileTitle": MessageLookupByLibrary.simpleMessage(
      "اجعل أنيس مناسبًا لك",
    ),
    "confirmPassword": MessageLookupByLibrary.simpleMessage(
      "تأكيد كلمة المرور",
    ),
    "continueAsGuest": MessageLookupByLibrary.simpleMessage("المتابعة كضيف"),
    "continuee": MessageLookupByLibrary.simpleMessage("المواصلة"),
    "createAccount": MessageLookupByLibrary.simpleMessage("إنشاء حساب"),
    "createSession": MessageLookupByLibrary.simpleMessage("إنشاء جلسة"),
    "createSessionAddRule": MessageLookupByLibrary.simpleMessage("أضف قاعدة"),
    "createSessionBasicInfo": MessageLookupByLibrary.simpleMessage(
      "المعلومات الأساسية",
    ),
    "createSessionCapacity": MessageLookupByLibrary.simpleMessage(
      "الحد الأقصى للأعضاء",
    ),
    "createSessionDescHint": MessageLookupByLibrary.simpleMessage(
      "ما الذي ستعمل عليه؟",
    ),
    "createSessionDescription": MessageLookupByLibrary.simpleMessage("الوصف"),
    "createSessionGiftHint": MessageLookupByLibrary.simpleMessage(
      "مثال: قهوة، وجبات خفيفة...",
    ),
    "createSessionGiftLabel": MessageLookupByLibrary.simpleMessage(
      "هدية / مكافأة",
    ),
    "createSessionPersons": MessageLookupByLibrary.simpleMessage("أشخاص"),
    "createSessionRuleHint": MessageLookupByLibrary.simpleMessage(
      "مثال: عدم الكلام بصوت عالٍ",
    ),
    "createSessionSubject": MessageLookupByLibrary.simpleMessage("المادة"),
    "createSessionSubjectHint": MessageLookupByLibrary.simpleMessage(
      "مثال: رياضيات، فيزياء...",
    ),
    "createSessionSubmit": MessageLookupByLibrary.simpleMessage("إنشاء الجلسة"),
    "createSessionTitle": MessageLookupByLibrary.simpleMessage(
      "إنشاء جلسة دراسية",
    ),
    "createSessionTopic": MessageLookupByLibrary.simpleMessage("الموضوع"),
    "createSessionTopicHint": MessageLookupByLibrary.simpleMessage(
      "مثال: الفصل الثالث من التفاضل",
    ),
    "createYourAccount": MessageLookupByLibrary.simpleMessage("أنشئ حسابك"),
    "currentPlan": MessageLookupByLibrary.simpleMessage("الخطة الحالية"),
    "currentSettings": MessageLookupByLibrary.simpleMessage(
      "الإعدادات الحالية",
    ),
    "currentWorkspace": MessageLookupByLibrary.simpleMessage(
      "الورك سبيس الحالي",
    ),
    "customMultiplierLabel": MessageLookupByLibrary.simpleMessage("مخصص"),
    "date": MessageLookupByLibrary.simpleMessage("التاريخ"),
    "dayCalculationHoursLabel": MessageLookupByLibrary.simpleMessage(
      "ساعات اليوم الدراسي",
    ),
    "dayHoursExplain": MessageLookupByLibrary.simpleMessage(
      "عدد ساعات الحضور التي تعادل يوم اشتراك كامل.",
    ),
    "daysLeft": m0,
    "des": MessageLookupByLibrary.simpleMessage("الوصف"),
    "description": MessageLookupByLibrary.simpleMessage("الـوصـف"),
    "dhuhr": MessageLookupByLibrary.simpleMessage("الظهر"),
    "doItLater": MessageLookupByLibrary.simpleMessage("لاحقًا"),
    "done": MessageLookupByLibrary.simpleMessage("تم"),
    "drinksMenu": MessageLookupByLibrary.simpleMessage("قائمة المشروبات"),
    "editSettings": MessageLookupByLibrary.simpleMessage("تعديل الإعدادات"),
    "email": MessageLookupByLibrary.simpleMessage("البريد الإكتروني"),
    "enterActivationCodeHint": MessageLookupByLibrary.simpleMessage(
      "أدخل الكود الذي استلمته",
    ),
    "enterEmailToResetPassword": MessageLookupByLibrary.simpleMessage(
      "ادخل البريد الالكتروني الخاص بك لاعادة تعيين كلمة المرور الجديدة",
    ),
    "enterOtp": MessageLookupByLibrary.simpleMessage(
      "ادخل رمز التحقق المرسل علي البريد الالكتروني",
    ),
    "error": MessageLookupByLibrary.simpleMessage("هناك خطأ ما"),
    "excellent": MessageLookupByLibrary.simpleMessage("ممتاز"),
    "facebookPage": MessageLookupByLibrary.simpleMessage("صفحة فيسبوك"),
    "fajr": MessageLookupByLibrary.simpleMessage("الفجر"),
    "feed": MessageLookupByLibrary.simpleMessage("التعليقات"),
    "female": MessageLookupByLibrary.simpleMessage("أنثى"),
    "fieldRequired": MessageLookupByLibrary.simpleMessage("هذا الحقل مطلوب"),
    "filterToday": MessageLookupByLibrary.simpleMessage("اليوم"),
    "findBuddy": MessageLookupByLibrary.simpleMessage("إيجاد رفيق"),
    "freeSubscription": MessageLookupByLibrary.simpleMessage("باقة مجانية"),
    "freeWorkspaceLabel": MessageLookupByLibrary.simpleMessage("مجاني  (0×)"),
    "full": MessageLookupByLibrary.simpleMessage("الاسم الكامل"),
    "fullNameHint": MessageLookupByLibrary.simpleMessage("اسمك الكامل"),
    "gallery": MessageLookupByLibrary.simpleMessage("المعرض"),
    "gender": MessageLookupByLibrary.simpleMessage("النوع"),
    "generalError": MessageLookupByLibrary.simpleMessage(
      "حدث خطأ. يرجى المحاولة مرة أخرى.",
    ),
    "getStarted": MessageLookupByLibrary.simpleMessage("ابدأ الآن"),
    "goldSubscription": MessageLookupByLibrary.simpleMessage("باقة ذهبية"),
    "good": MessageLookupByLibrary.simpleMessage("جيد"),
    "goodAfternoon": MessageLookupByLibrary.simpleMessage("مساء الخير"),
    "goodEvening": MessageLookupByLibrary.simpleMessage("مساء النور"),
    "goodMorning": MessageLookupByLibrary.simpleMessage("صباح الخير"),
    "groups": MessageLookupByLibrary.simpleMessage("المجموعات"),
    "guestRestrictedBody": MessageLookupByLibrary.simpleMessage(
      "هذه الميزة متاحة للطلاب المسجلين. انضم إلى أنيس مجاناً للوصول إلى جلسات الدراسة، ومطابقة الرفيق، والمساحات المميزة.",
    ),
    "guestRestrictedTitle": MessageLookupByLibrary.simpleMessage("للأعضاء فقط"),
    "hajj": MessageLookupByLibrary.simpleMessage("نشاط الحج"),
    "haveActivationCode": MessageLookupByLibrary.simpleMessage("لدي كود تفعيل"),
    "home": MessageLookupByLibrary.simpleMessage("الرئيسية"),
    "homeTab": MessageLookupByLibrary.simpleMessage("الرئيسية"),
    "hour": MessageLookupByLibrary.simpleMessage("ساعة"),
    "hourMultiplierExplain": MessageLookupByLibrary.simpleMessage(
      "كل ساعة حضور حقيقية تُحتسب بهذا المعامل من رصيد اشتراك الطالب.",
    ),
    "hourMultiplierLabel": MessageLookupByLibrary.simpleMessage("معامل الساعة"),
    "hours": MessageLookupByLibrary.simpleMessage("ساعات"),
    "hoursStudiedToday": MessageLookupByLibrary.simpleMessage(
      "ساعات الدراسة اليوم",
    ),
    "inProgress": MessageLookupByLibrary.simpleMessage("قيد التنفيذ"),
    "infoTab": MessageLookupByLibrary.simpleMessage("المعلومات"),
    "interestEngineering": MessageLookupByLibrary.simpleMessage("الهندسة"),
    "interestHint": MessageLookupByLibrary.simpleMessage("مثال: التصميم"),
    "interestLanguages": MessageLookupByLibrary.simpleMessage("اللغات"),
    "interestMathematics": MessageLookupByLibrary.simpleMessage("الرياضيات"),
    "interestMedicine": MessageLookupByLibrary.simpleMessage("الطب"),
    "interestProgramming": MessageLookupByLibrary.simpleMessage("البرمجة"),
    "interests": MessageLookupByLibrary.simpleMessage("اهتمامات الدراسة"),
    "invalidEmail": MessageLookupByLibrary.simpleMessage(
      " أدخـل بـريد الكــتروني صَحـيح ",
    ),
    "invalidWorkspaceQr": MessageLookupByLibrary.simpleMessage(
      "رمز QR هذا ليس لمساحة دراسة صالحة في أنيس.",
    ),
    "isha": MessageLookupByLibrary.simpleMessage("العشاء"),
    "joinSession": MessageLookupByLibrary.simpleMessage("انضم"),
    "juz": MessageLookupByLibrary.simpleMessage("جزء"),
    "kaaba": MessageLookupByLibrary.simpleMessage("طواف الكعبة"),
    "km": MessageLookupByLibrary.simpleMessage("كم"),
    "languageArabic": MessageLookupByLibrary.simpleMessage("العربية"),
    "languageEnglish": MessageLookupByLibrary.simpleMessage("English"),
    "languageTurkish": MessageLookupByLibrary.simpleMessage("التركية"),
    "lastSeen": MessageLookupByLibrary.simpleMessage("آخر ظهور"),
    "later": MessageLookupByLibrary.simpleMessage("لاحقا"),
    "leaveWorkspace": MessageLookupByLibrary.simpleMessage("مغادرة الورك سبيس"),
    "liveNow": MessageLookupByLibrary.simpleMessage("مباشر الآن"),
    "loading": MessageLookupByLibrary.simpleMessage("جاري التحميل"),
    "loc": MessageLookupByLibrary.simpleMessage(
      "خدمة تحديد الموقع مرفوضة للأبد!",
    ),
    "locSer": MessageLookupByLibrary.simpleMessage("تم رفض إذن خدمة الموقع"),
    "locateViaGps": MessageLookupByLibrary.simpleMessage(
      "تحديد الموقع عبر الـ GPS",
    ),
    "locateYourself": MessageLookupByLibrary.simpleMessage("تحديد موقعك"),
    "locateYourselfDesc": MessageLookupByLibrary.simpleMessage(
      "اختر موقعك لنظهر لك المساحات الأقرب إليك ونحسب المسافات بدقة",
    ),
    "locating": MessageLookupByLibrary.simpleMessage("جاري تحديد موقعك..."),
    "location": MessageLookupByLibrary.simpleMessage("المقر"),
    "locationError": MessageLookupByLibrary.simpleMessage("الموقع خطآ"),
    "logOut": MessageLookupByLibrary.simpleMessage("تَســجيل الخــروج"),
    "login": MessageLookupByLibrary.simpleMessage("تسجيل الدخول"),
    "loginOrRegister": MessageLookupByLibrary.simpleMessage(
      "تسجيل الدخول / إنشاء حساب",
    ),
    "logout": MessageLookupByLibrary.simpleMessage("تسجيل الخروج"),
    "logoutQuestion": MessageLookupByLibrary.simpleMessage(
      "هل أنت متأكد من رغبتك في تسجيل الخروج؟",
    ),
    "maghrib": MessageLookupByLibrary.simpleMessage("المغرب"),
    "male": MessageLookupByLibrary.simpleMessage("ذكر"),
    "manageSubscription": MessageLookupByLibrary.simpleMessage(
      "إدارة الاشتراك",
    ),
    "memberInterests": MessageLookupByLibrary.simpleMessage("الاهتمامات"),
    "memberRating": MessageLookupByLibrary.simpleMessage("التقييم"),
    "myWorkspace": MessageLookupByLibrary.simpleMessage("مساحتي"),
    "name": MessageLookupByLibrary.simpleMessage("الاسم:"),
    "nameTooShortError": MessageLookupByLibrary.simpleMessage(
      "الاسم يجب أن يكون حرفين على الأقل",
    ),
    "nameWillAppearOnProfile": MessageLookupByLibrary.simpleMessage(
      "سيظهر اسمك في ملفك الشخصي وجلسات الدراسة.",
    ),
    "nearbyFilter": MessageLookupByLibrary.simpleMessage("قريب منك"),
    "next": MessageLookupByLibrary.simpleMessage("التالي"),
    "noBuddiesFound": MessageLookupByLibrary.simpleMessage(
      "لم يتم العثور على رفاق",
    ),
    "noData": MessageLookupByLibrary.simpleMessage(
      "لا يــوجد شـئ في الوقت الحـالي",
    ),
    "noRating": MessageLookupByLibrary.simpleMessage("لا تقييم"),
    "noSessionsInWorkspace": MessageLookupByLibrary.simpleMessage(
      "لا توجد جلسات نشطة في هذه المساحة",
    ),
    "noSessionsToday": MessageLookupByLibrary.simpleMessage(
      "لا توجد جلسات اليوم",
    ),
    "noWorkspacesFound": MessageLookupByLibrary.simpleMessage(
      "لم يتم العثور على مساحات",
    ),
    "notification": MessageLookupByLibrary.simpleMessage("الإشعارات"),
    "onboarding1Desc": MessageLookupByLibrary.simpleMessage(
      "بنفس الاشتراك، ادخل أي ورك سبيس قريب منك وذاكر في بيئة تركيز مثالية — بدون تكاليف إضافية أبداً.",
    ),
    "onboarding1Title": MessageLookupByLibrary.simpleMessage(
      "اشتراك واحد — كل المساحات",
    ),
    "onboarding2Desc": MessageLookupByLibrary.simpleMessage(
      "اكتشف الجلسات المتاحة في الورك سبيسات القريبة واشترك مع زملاء يدرسون نفس المادة.",
    ),
    "onboarding2Title": MessageLookupByLibrary.simpleMessage(
      "انضم لجلسات دراسية حية",
    ),
    "onboarding3Desc": MessageLookupByLibrary.simpleMessage(
      "دور على رفيق دراسة يناسب مستواك، أو انضم لمجموعة — تذاكروا مع بعض وتعلموا حاجة جديدة.",
    ),
    "onboarding3Title": MessageLookupByLibrary.simpleMessage("ابحث عن أنيسك"),
    "onlineNow": MessageLookupByLibrary.simpleMessage("متصل الآن"),
    "open": MessageLookupByLibrary.simpleMessage("افتح خريطة جوجل"),
    "openInMaps": MessageLookupByLibrary.simpleMessage("افتح في الخرائط"),
    "openNow": MessageLookupByLibrary.simpleMessage("مفتوح الآن"),
    "openSpot": MessageLookupByLibrary.simpleMessage("مقعد متاح"),
    "or": MessageLookupByLibrary.simpleMessage("أو"),
    "orChooseRegionManually": MessageLookupByLibrary.simpleMessage(
      "أو اختر منطقة يدوياً",
    ),
    "orderNumber": MessageLookupByLibrary.simpleMessage("رقم الطلب "),
    "otp": MessageLookupByLibrary.simpleMessage("رمز التحقق"),
    "ownerPanelTitle": MessageLookupByLibrary.simpleMessage("لوحة تحكم المالك"),
    "password": MessageLookupByLibrary.simpleMessage("كلمة المرور"),
    "passwordEmptyError": MessageLookupByLibrary.simpleMessage(
      "كلمة المرور مطلوبة",
    ),
    "passwordHint": MessageLookupByLibrary.simpleMessage("••••••••"),
    "passwordMissingNumberError": MessageLookupByLibrary.simpleMessage(
      "كلمة المرور يجب أن تحتوي على رقم",
    ),
    "passwordsDoNotMatch": MessageLookupByLibrary.simpleMessage(
      "كلمتا المرور غير متطابقتين",
    ),
    "phoneHint": MessageLookupByLibrary.simpleMessage("+20 1XX XXX XXXX"),
    "phoneNumber": MessageLookupByLibrary.simpleMessage("رقم الهاتف"),
    "phoneRequired": MessageLookupByLibrary.simpleMessage("رقم الهاتف مطلوب"),
    "planActivatedSuccessfully": MessageLookupByLibrary.simpleMessage(
      "تم تفعيل الباقة بنجاح",
    ),
    "planBestValue": MessageLookupByLibrary.simpleMessage("أفضل قيمة"),
    "planCtaFree": MessageLookupByLibrary.simpleMessage("ابدأ مجاناً"),
    "planCtaGold": MessageLookupByLibrary.simpleMessage("اشترك في الذهبي"),
    "planCtaSilver": MessageLookupByLibrary.simpleMessage("اشترك في الفضي"),
    "planCurrentBadge": MessageLookupByLibrary.simpleMessage("الحالي"),
    "planFeatureAds": MessageLookupByLibrary.simpleMessage("يحتوي على إعلانات"),
    "planFeatureAnalytics": MessageLookupByLibrary.simpleMessage(
      "تحليلات الدراسة",
    ),
    "planFeatureBuddies": MessageLookupByLibrary.simpleMessage("حتى 3 رفقاء"),
    "planFeatureGoldHours": MessageLookupByLibrary.simpleMessage(
      "٢٠٠ ساعة شهرياً",
    ),
    "planFeatureNoAds": MessageLookupByLibrary.simpleMessage("بدون إعلانات"),
    "planFeaturePriority": MessageLookupByLibrary.simpleMessage(
      "دعم ذو أولوية",
    ),
    "planFeatureSessions2": MessageLookupByLibrary.simpleMessage(
      "حتى جلستين/شهر",
    ),
    "planFeatureSilverHours": MessageLookupByLibrary.simpleMessage(
      "١٢٠ ساعة شهرياً",
    ),
    "planFeatureWorkspaces": MessageLookupByLibrary.simpleMessage(
      "مساحات محدودة",
    ),
    "planFeatureWorkspacesUnlimited": MessageLookupByLibrary.simpleMessage(
      "جميع المساحات",
    ),
    "planFreeTitle": MessageLookupByLibrary.simpleMessage("مجاني"),
    "planGoldTitle": MessageLookupByLibrary.simpleMessage("ذهبي"),
    "planMostPopular": MessageLookupByLibrary.simpleMessage("الأكثر شيوعاً"),
    "planPriceFree": MessageLookupByLibrary.simpleMessage("0 جنيه"),
    "planPriceGold": MessageLookupByLibrary.simpleMessage("٢,٣٠٠ جنيه/شهر"),
    "planPriceSilver": MessageLookupByLibrary.simpleMessage("١,٧٠٠ جنيه/شهر"),
    "planSilverTitle": MessageLookupByLibrary.simpleMessage("فضي"),
    "please": MessageLookupByLibrary.simpleMessage("الرجاء تفعيل خدمة الموقع"),
    "pleaseEndterValue": MessageLookupByLibrary.simpleMessage(
      "لا يمكنك ترك هذا الحقل فارغ",
    ),
    "poor": MessageLookupByLibrary.simpleMessage("ضعيف"),
    "premiumWorkspaceLabel": MessageLookupByLibrary.simpleMessage("مميز  (2×)"),
    "previewNote": m1,
    "price": MessageLookupByLibrary.simpleMessage("السعر"),
    "privacyPolicy": MessageLookupByLibrary.simpleMessage("سياسة الخصوصية"),
    "profile": MessageLookupByLibrary.simpleMessage("حسابي"),
    "profileEmailHint": MessageLookupByLibrary.simpleMessage(
      "you@university.edu.eg",
    ),
    "profileGenderReason": MessageLookupByLibrary.simpleMessage(
      "يساعدنا في تخصيص ملفك وترشيحاتك.",
    ),
    "profileInterestsReason": MessageLookupByLibrary.simpleMessage(
      "اختر الموضوعات التي تستمتع بدراستها مع الآخرين.",
    ),
    "profileTab": MessageLookupByLibrary.simpleMessage("حسابي"),
    "profileUniversityHint": MessageLookupByLibrary.simpleMessage(
      "جامعتك أو معهدك",
    ),
    "purchasePlanManualDescription": MessageLookupByLibrary.simpleMessage(
      "تواصل معنا وادفع نقداً. بعد استلام المبلغ سنرسل لك كود التفعيل. الدفع البنكي سيضاف لاحقاً.",
    ),
    "purchasePlanManually": MessageLookupByLibrary.simpleMessage(
      "شراء الباقة يدوياً",
    ),
    "purchasePlanMessage": m2,
    "regionDokki": MessageLookupByLibrary.simpleMessage("الدقي، الجيزة"),
    "regionFifthSettlement": MessageLookupByLibrary.simpleMessage(
      "التجمع الخامس، القاهرة",
    ),
    "regionNasrCity": MessageLookupByLibrary.simpleMessage(
      "مدينة نصر، القاهرة",
    ),
    "regionOctober": MessageLookupByLibrary.simpleMessage("٦ أكتوبر، الجيزة"),
    "regionSmouha": MessageLookupByLibrary.simpleMessage("سموحة، الإسكندرية"),
    "register": MessageLookupByLibrary.simpleMessage("تسجيل"),
    "requestToLeave": MessageLookupByLibrary.simpleMessage("طلب الخروج"),
    "resetPassword": MessageLookupByLibrary.simpleMessage(
      "استعادة كلمة المرور",
    ),
    "resultsCount": m3,
    "retry": MessageLookupByLibrary.simpleMessage("إعادة المحاولة"),
    "round": MessageLookupByLibrary.simpleMessage("وقت الوصول"),
    "saveAndContinue": MessageLookupByLibrary.simpleMessage("حفظ ومتابعة"),
    "saveSettings": MessageLookupByLibrary.simpleMessage("حفظ الإعدادات"),
    "savingSettings": MessageLookupByLibrary.simpleMessage("جارٍ الحفظ..."),
    "scanQrShort": MessageLookupByLibrary.simpleMessage("امسح QR"),
    "scanQrToCheckIn": MessageLookupByLibrary.simpleMessage(
      "امسح QR الورك سبيس لتسجيل الدخول",
    ),
    "scanToCheckIn": MessageLookupByLibrary.simpleMessage(
      "امسح الرمز لتسجيل الدخول",
    ),
    "searchWorkspace": MessageLookupByLibrary.simpleMessage("ابحث عن مساحة..."),
    "selectGenderError": MessageLookupByLibrary.simpleMessage(
      "يرجى اختيار الجنس",
    ),
    "selectLanguage": MessageLookupByLibrary.simpleMessage("أختيار اللغة"),
    "selectWorkspace": MessageLookupByLibrary.simpleMessage("اختر مساحة"),
    "selectWorkspaceHint": MessageLookupByLibrary.simpleMessage(
      "اضغط لاختيار مساحة",
    ),
    "send": MessageLookupByLibrary.simpleMessage("أرسال"),
    "sendOtp": MessageLookupByLibrary.simpleMessage("تاكيد رمز التحقق"),
    "sending": MessageLookupByLibrary.simpleMessage("جاري الإرسال..."),
    "sendingCheckoutRequest": MessageLookupByLibrary.simpleMessage(
      "جاري إرسال الطلب...",
    ),
    "services": MessageLookupByLibrary.simpleMessage("الخدمات"),
    "sessionDate": MessageLookupByLibrary.simpleMessage("التاريخ"),
    "sessionDescription": MessageLookupByLibrary.simpleMessage("عن هذه الجلسة"),
    "sessionFounder": MessageLookupByLibrary.simpleMessage("المؤسس"),
    "sessionFull": MessageLookupByLibrary.simpleMessage("الجلسة ممتلئة"),
    "sessionGift": MessageLookupByLibrary.simpleMessage("الهدية"),
    "sessionInProgress": MessageLookupByLibrary.simpleMessage("جلسة جارية"),
    "sessionJoined": MessageLookupByLibrary.simpleMessage("انضممت"),
    "sessionMembers": MessageLookupByLibrary.simpleMessage("الأعضاء"),
    "sessionOpen": MessageLookupByLibrary.simpleMessage("مفتوحة"),
    "sessionPlace": MessageLookupByLibrary.simpleMessage("الموقع"),
    "sessionRules": MessageLookupByLibrary.simpleMessage("قواعد الجلسة"),
    "sessionTabOverview": MessageLookupByLibrary.simpleMessage("نظرة عامة"),
    "sessionTabPeople": MessageLookupByLibrary.simpleMessage("الأشخاص"),
    "sessionTime": MessageLookupByLibrary.simpleMessage("الوقت"),
    "sessionsCount": MessageLookupByLibrary.simpleMessage("عدد الجلسات"),
    "sessionsTab": MessageLookupByLibrary.simpleMessage("الجلسات"),
    "settings": MessageLookupByLibrary.simpleMessage("الاعدادات"),
    "settingsSaved": MessageLookupByLibrary.simpleMessage(
      "تم حفظ الإعدادات بنجاح",
    ),
    "show": MessageLookupByLibrary.simpleMessage("عرض الكل"),
    "signIn": MessageLookupByLibrary.simpleMessage("تسجيل الدخول"),
    "signInToSeeProfile": MessageLookupByLibrary.simpleMessage(
      "سجل الدخول لبناء ملفك الدراسي",
    ),
    "signInToSeeProfileSubtitle": MessageLookupByLibrary.simpleMessage(
      "ملفك وتقدمك وشاراتك وتفاصيل اشتراكك متاحة بعد تسجيل الدخول.",
    ),
    "silverSubscription": MessageLookupByLibrary.simpleMessage("باقة فضية"),
    "size": MessageLookupByLibrary.simpleMessage("الحجم"),
    "skip": MessageLookupByLibrary.simpleMessage("تخطي المقدمة"),
    "specialization": MessageLookupByLibrary.simpleMessage("التخصص / المجال"),
    "specializationHint": MessageLookupByLibrary.simpleMessage(
      "مثال: علوم الحاسب، الطب...",
    ),
    "splashArabicHint": MessageLookupByLibrary.simpleMessage("عربي"),
    "splashChooseLanguage": MessageLookupByLibrary.simpleMessage("اختر لغتك"),
    "splashEnglishHint": MessageLookupByLibrary.simpleMessage("English"),
    "splashScreenText": MessageLookupByLibrary.simpleMessage(
      "اشتراك واحد يفتحلك أي ورك سبيس قريب منك، وجلسات دراسية حية، ورفيق مذاكرة مناسب.",
    ),
    "splashTagline": MessageLookupByLibrary.simpleMessage(
      "ورك سبيس  ·  جلسة  ·  رفيق",
    ),
    "splashTitle": MessageLookupByLibrary.simpleMessage("أنيس"),
    "standardWorkspaceLabel": MessageLookupByLibrary.simpleMessage(
      "قياسي  (1×)",
    ),
    "startNow": MessageLookupByLibrary.simpleMessage("ابدأ الآن"),
    "status": MessageLookupByLibrary.simpleMessage("الحالة"),
    "stepIndicator": m4,
    "streakDays": MessageLookupByLibrary.simpleMessage("أيام متواصلة"),
    "strongPasswordHint": MessageLookupByLibrary.simpleMessage(
      "استخدم 8 أحرف على الأقل مع حرف كبير وحرف صغير ورقم.",
    ),
    "studyBuddy": MessageLookupByLibrary.simpleMessage("رفيق دراسة"),
    "studyTimeLabel": MessageLookupByLibrary.simpleMessage("وقت الدراسة"),
    "subjectHint": MessageLookupByLibrary.simpleMessage("ابحث بالمادة..."),
    "subscribe": MessageLookupByLibrary.simpleMessage("اشترك الآن"),
    "subscriptionDaysAndHours": m5,
    "subscriptionDaysLeft": MessageLookupByLibrary.simpleMessage(
      "أيام الاشتراك المتبقية",
    ),
    "subscriptionDaysProgressValue": m6,
    "subscriptionDaysUsed": m7,
    "subscriptionProgress": MessageLookupByLibrary.simpleMessage("الاشتراك"),
    "subscriptionTotalDaysLabel": m8,
    "success": MessageLookupByLibrary.simpleMessage("نجح"),
    "sunrise": MessageLookupByLibrary.simpleMessage("الشروق"),
    "thankFeed": MessageLookupByLibrary.simpleMessage(
      "شكرا لك على إرسال تعليقك.",
    ),
    "thisWeek": MessageLookupByLibrary.simpleMessage("هذا الأسبوع"),
    "today": MessageLookupByLibrary.simpleMessage("إشعارات اليوم"),
    "todaysSessions": MessageLookupByLibrary.simpleMessage("جلسات اليوم"),
    "totalEarlyDepartureHours": MessageLookupByLibrary.simpleMessage(
      "إجمالي ساعات الخروج المبكر",
    ),
    "totalLateHours": MessageLookupByLibrary.simpleMessage(
      "إجمالي ساعات التأخير",
    ),
    "totalOvertimeHours": MessageLookupByLibrary.simpleMessage(
      "إجمالي ساعات الوقت الإضافي",
    ),
    "totalStudyHours": MessageLookupByLibrary.simpleMessage(
      "إجمالي ساعات الدراسة",
    ),
    "totalWorkHours": MessageLookupByLibrary.simpleMessage(
      "إجمالي ساعات العمل",
    ),
    "tryDifferentFilters": MessageLookupByLibrary.simpleMessage(
      "جرب فلاتر مختلفة",
    ),
    "tryLater": MessageLookupByLibrary.simpleMessage(
      "الرجاء المحاولة مرة اخري فيما بعد",
    ),
    "type": MessageLookupByLibrary.simpleMessage("النوع:"),
    "university": MessageLookupByLibrary.simpleMessage("الجامعة"),
    "universityHint": MessageLookupByLibrary.simpleMessage("ابحث بالجامعة..."),
    "unknown": MessageLookupByLibrary.simpleMessage("غير معروف"),
    "upcoming": MessageLookupByLibrary.simpleMessage("قريبا"),
    "upcomingSessions": MessageLookupByLibrary.simpleMessage("الجلسات القادمة"),
    "update": MessageLookupByLibrary.simpleMessage("تحديث"),
    "updateAvailable": MessageLookupByLibrary.simpleMessage(
      "تحـديث جديد متاح ",
    ),
    "updateBody": MessageLookupByLibrary.simpleMessage(
      "هنــاك تحـديث جديد من TEAA التحــديث للاستمرار",
    ),
    "upgradePlan": MessageLookupByLibrary.simpleMessage("ترقية الخطة"),
    "userName": MessageLookupByLibrary.simpleMessage("أسم المستخدم"),
    "veryGood": MessageLookupByLibrary.simpleMessage("جيد جداً"),
    "viewAll": MessageLookupByLibrary.simpleMessage("رؤية الكل"),
    "waiting": MessageLookupByLibrary.simpleMessage("الانتظار"),
    "welcome": MessageLookupByLibrary.simpleMessage("أهلا بعودتك,"),
    "whatsAppNumber": MessageLookupByLibrary.simpleMessage("رقم واتساب"),
    "whatsAppNumberHint": MessageLookupByLibrary.simpleMessage(
      "+20 1XX XXX XXXX",
    ),
    "whatsAppNumberRequired": MessageLookupByLibrary.simpleMessage(
      "رقم واتساب مطلوب",
    ),
    "whatsappNumber": m9,
    "workspaceBusy": MessageLookupByLibrary.simpleMessage("مشغول"),
    "workspaceCapacity": MessageLookupByLibrary.simpleMessage(
      "الطاقة الاستيعابية",
    ),
    "workspaceDailyCapText": m10,
    "workspaceDayCalculationTitle": MessageLookupByLibrary.simpleMessage(
      "طريقة احتساب اليوم في هذه المساحة",
    ),
    "workspaceFull": MessageLookupByLibrary.simpleMessage("ممتلئ"),
    "workspaceHourMultiplierCustom": m11,
    "workspaceHourMultiplierFree": MessageLookupByLibrary.simpleMessage(
      "مساحة عمل مجانية (ساعة الحضور = ٠ ساعة اشتراك)",
    ),
    "workspaceHourMultiplierPremium": MessageLookupByLibrary.simpleMessage(
      "مساحة عمل مميزة (ساعة الحضور = ساعتي اشتراك)",
    ),
    "workspaceHourMultiplierStandard": MessageLookupByLibrary.simpleMessage(
      "مساحة عمل قياسية (ساعة الحضور = ساعة اشتراك)",
    ),
    "workspaceOpen": MessageLookupByLibrary.simpleMessage("متاح"),
    "workspaceSettingsTitle": MessageLookupByLibrary.simpleMessage(
      "إعدادات المساحة",
    ),
    "workspacesSubtitle": MessageLookupByLibrary.simpleMessage(
      "اعثر على المكان المثالي للدراسة مع رفيقك",
    ),
    "workspacesTab": MessageLookupByLibrary.simpleMessage("مساحات"),
    "yourAccount": MessageLookupByLibrary.simpleMessage("حسابك"),
  };
}

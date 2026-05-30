import 'package:flutter/material.dart';
import '../core/constants/asset_resoures.dart';
import '../core/constants/colors.dart';

class Dummy {

  /// Dummy Activities Meals list

  ///
  /// /// Prayer times data provider

  // static const List<PrayerTime> defaultPrayerTimes = [
  //   PrayerTime(name: 'Fajr', time: '5:13 AM', icon: AssetRes.fajr),
  //   PrayerTime(name: 'Sunrise', time: '6:46 AM', icon: AssetRes.sunrise),
  //   PrayerTime(name: 'Dhuhr', time: '11:52 AM', icon: AssetRes.dhuhr),
  //   PrayerTime(name: 'Asr', time: '2:40 PM', icon: AssetRes.asr),
  //   PrayerTime(name: 'Maghrib', time: '4:59 PM', icon: AssetRes.maghrib),
  //   PrayerTime(name: 'Isha', time: '6:22 PM', icon: AssetRes.isha),
  // ];

  // static List<ActivityCard> defaultActivities = [
  //   ActivityCard(
  //     badgeLabel: S.current.inProgress,
  //     title: S.current.hajj,
  //     activityId: "2",
  //     time: "25/24/2025 2:00 PM",
  //   )
  // ];

  // static final List<FaqItem> faqItems = [
  //   FaqItem(
  //     questionEn: "How can I contact customer support during my trip?",
  //     questionAr: "كيف يمكنني التواصل مع خدمة العملاء أثناء رحلتي؟",
  //     answerEn:
  //     "You can contact our support team 24/7 through the app. Go to the Support section and submit a ticket, or use the live chat feature. For urgent matters, call our emergency hotline number provided in your booking confirmation.",
  //     answerAr:
  //     "يمكنك التواصل مع فريق الدعم لدينا على مدار 24/7 من خلال التطبيق. انتقل إلى قسم الدعم وقدم تذكرة، أو استخدم ميزة الدردشة المباشرة. للأمور العاجلة، اتصل برقم الخط الساخن للطوارئ المذكور في تأكيد حجزك.",
  //     icon: Icons.support_agent,
  //   ),
  //   FaqItem(
  //     questionEn: "What should I do if I have issues with my hotel room?",
  //     questionAr: "ماذا أفعل إذا واجهت مشاكل في غرفة الفندق؟",
  //     answerEn:
  //     "First, contact the hotel front desk directly to report the issue. If the problem isn't resolved, submit a support request through our app with photos and details. Our team will coordinate with the hotel management to ensure your comfort.",
  //     answerAr:
  //     "أولاً، اتصل بمكتب الاستقبال في الفندق مباشرة للإبلاغ عن المشكلة. إذا لم يتم حل المشكلة، قدم طلب دعم من خلال تطبيقنا مع صور وتفاصيل. سيقوم فريقنا بالتنسيق مع إدارة الفندق لضمان راحتك.",
  //     icon: Icons.hotel,
  //   ),
  //   FaqItem(
  //     questionEn: "How can I change or modify my tour schedule?",
  //     questionAr: "كيف يمكنني تغيير أو تعديل جدول جولتي؟",
  //     answerEn:
  //     "Tour schedule changes depend on availability and timing. Contact your supervisor or submit a request through the app at least 24 hours before the scheduled activity. Some changes may incur additional fees or may not be possible due to group arrangements.",
  //     answerAr:
  //     "تغييرات جدول الجولة تعتمد على التوفر والتوقيت. اتصل بمشرفك أو قدم طلباً من خلال التطبيق قبل 24 ساعة على الأقل من النشاط المجدول. قد تترتب رسوم إضافية على بعض التغييرات أو قد لا تكون ممكنة بسبب ترتيبات المجموعة.",
  //     icon: Icons.schedule,
  //   ),
  //   FaqItem(
  //     questionEn: "What do I do if I get separated from my tour group?",
  //     questionAr: "ماذا أفعل إذا انفصلت عن مجموعة الجولة؟",
  //     answerEn:
  //     "Don't panic! Check your app for your supervisor's contact number and group meeting points. Call your supervisor immediately or go to the nearest designated meeting point. Always carry your group ID card and emergency contact information.",
  //     answerAr:
  //     "لا تقلق! تحقق من التطبيق للحصول على رقم اتصال مشرفك ونقاط لقاء المجموعة. اتصل بمشرفك فوراً أو اذهب إلى أقرب نقطة لقاء محددة. احمل دائماً بطاقة هوية مجموعتك ومعلومات الاتصال للطوارئ.",
  //     icon: Icons.groups,
  //   ),
  // ];

  /// Sample notification data
  // static List<NotificationEntity> notifications = [
  //   NotificationEntity(
  //     id: 1,
  //     title: 'تم الموافقة علي طلبك رقم #56745',
  //     description:
  //     'تم الموافقة علي طلبك رقم #13242 الخاص باذن خروج بتاريخ 29/1/2026',
  //     isApproved: true,
  //     isRead: false,
  //   ),
  //   NotificationEntity(
  //     id: 2,
  //     title: 'تم رفض طلبك رقم #32423',
  //     description: '',
  //     isApproved: false,
  //     isRead: true,
  //   ),
  //   NotificationEntity(
  //     id: 3,
  //     title: 'تم رفض طلبك رقم #32423',
  //     description: '',
  //     isApproved: false,
  //     isRead: true,
  //   ),
  //   NotificationEntity(
  //     id: 4,
  //     title: 'تم رفض طلبك رقم #32423',
  //     description: '',
  //     isApproved: false,
  //     isRead: true,
  //   ),
  //   NotificationEntity(
  //     id: 5,
  //     title: 'تم رفض طلبك رقم #32423',
  //     description: '',
  //     isApproved: false,
  //     isRead: true,
  //   ),
  // ];

  static List<Map<String, dynamic>> orders = [
    {
      'status': 'approved',
      'statusColor': ColorRes.staticGreenColor,
      'orderNumber': '#VL2110',
      'date': '07 Oct, 2021',
      'type': 'study order',
    },
    {
      'status': 'waiting',
      'statusColor': ColorRes.staticBlueColor,
      'orderNumber': '#VL2111',
      'date': '07 Oct, 2021',
      'type': 'order viewing',
    },
    {
      'status': 'rejectet',
      'statusColor': ColorRes.staticYellowColor,
      'orderNumber': '#VL2112',
      'date': '07 Oct, 2021',
      'type': 'order left',
    },
    {
      'status': 'deleted',
      'statusColor': ColorRes.staticRedColor,
      'orderNumber': '#VL2113',
      'date': '07 Oct, 2021',
      'type': 'loan ',
    },
    {
      'status': 'deleted',
      'statusColor': ColorRes.staticRedColor,
      'orderNumber': '#VL2113',
      'date': '07 Oct, 2021',
      'type': 'loan ',
    },
    {
      'status': 'deleted',
      'statusColor': ColorRes.staticRedColor,
      'orderNumber': '#VL2113',
      'date': '07 Oct, 2021',
      'type': 'loan ',
    },
  ];

}

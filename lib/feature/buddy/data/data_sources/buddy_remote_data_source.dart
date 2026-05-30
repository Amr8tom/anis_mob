import '../model/buddy_member_model.dart';
import '../model/buddy_session_model.dart';
import '../../domain/entity/buddy_session_entity.dart';

abstract class BuddyRemoteDataSource {
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  });
  Future<bool> joinSession(String sessionId);
  Future<bool> createSession(Map<String, dynamic> data);
}

class BuddyRemoteDataSourceImpl implements BuddyRemoteDataSource {
  static final _now = DateTime.now();

  static final List<BuddySessionModel> _allSessions = [
    BuddySessionModel(
      id: 'buddy_001',
      buddyName: 'سارة علي',
      buddyInitials: 'س.ع',
      avatarColorKey: 'red',
      university: 'جامعة القاهرة',
      availability: BuddyAvailability.online,
      topic: 'حل مسائل التفاضل والتكامل',
      subject: 'التفاضل والتكامل',
      description:
          'جلسة دراسة جماعية لحل مسائل التفاضل والتكامل — سنغطي القواعد الأساسية والتطبيقات العملية. يُرحب بجميع المستويات!',
      rules: [
        'الصمت الكامل أثناء الشرح',
        'أحضر كتبك ومعادلاتك',
        'لا للهاتف أثناء الجلسة',
      ],
      gift: '☕ فنجان قهوة مجاني لكل مشارك',
      members: [
        BuddyMemberModel(
          id: 'm1', name: 'سارة علي', initials: 'س.ع', avatarColorKey: 'red',
          university: 'جامعة القاهرة', studyField: 'هندسة رياضية',
          interests: ['رياضيات', 'فيزياء', 'برمجة'], rating: 4.8, totalSessions: 23, isFounder: true,
        ),
        BuddyMemberModel(
          id: 'm2', name: 'كريم مصطفى', initials: 'ك.م', avatarColorKey: 'blue',
          university: 'جامعة القاهرة', studyField: 'إحصاء تطبيقي',
          interests: ['رياضيات', 'إحصاء'], rating: 4.3, totalSessions: 11,
        ),
        BuddyMemberModel(
          id: 'm3', name: 'ياسمين حسن', initials: 'ي.ح', avatarColorKey: 'purple',
          university: 'جامعة عين شمس', studyField: 'علوم حاسب',
          interests: ['رياضيات', 'خوارزميات'], rating: 4.6, totalSessions: 17,
        ),
      ],
      maxCapacity: 6,
      startTime: DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day, 15, 0),
      timeLabel: 'اليوم • 3:00 م',
      workspaceId: 'ws_001',
      workspaceName: 'مكتبة الجامعة الأمريكية',
      workspaceAddress: 'التجمع الخامس، القاهرة الجديدة',
      sessionStatus: BuddySessionStatus.open,
    ),
    BuddySessionModel(
      id: 'buddy_002',
      buddyName: 'محمد حسن',
      buddyInitials: 'م.ح',
      avatarColorKey: 'blue',
      university: 'جامعة عين شمس',
      availability: BuddyAvailability.busy,
      topic: 'مراجعة الفيزياء الحديثة',
      subject: 'الفيزياء الحديثة',
      description:
          'مراجعة مكثفة لأبرز موضوعات الفيزياء الحديثة: نظرية النسبية، ميكانيكا الكم، والتطبيقات المعاصرة.',
      rules: [
        'احضر اللاب توب',
        'تمرّن على المسائل قبل الجلسة',
        'لا يُقبل التأخر أكثر من ١٠ دقائق',
      ],
      members: [
        BuddyMemberModel(
          id: 'm4', name: 'محمد حسن', initials: 'م.ح', avatarColorKey: 'blue',
          university: 'جامعة عين شمس', studyField: 'فيزياء نظرية',
          interests: ['فيزياء', 'رياضيات', 'فلسفة العلوم'], rating: 4.9, totalSessions: 41, isFounder: true,
        ),
        BuddyMemberModel(
          id: 'm5', name: 'لمياء سعيد', initials: 'ل.س', avatarColorKey: 'green',
          university: 'جامعة عين شمس', studyField: 'فيزياء تطبيقية',
          interests: ['كم', 'اشعاع'], rating: 4.4, totalSessions: 9,
        ),
      ],
      maxCapacity: 4,
      startTime: DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day, 17, 30),
      timeLabel: 'اليوم • 5:30 م',
      workspaceId: 'ws_002',
      workspaceName: 'مساحة كوورك داون تاون',
      workspaceAddress: 'وسط البلد، القاهرة',
      sessionStatus: BuddySessionStatus.open,
    ),
    BuddySessionModel(
      id: 'buddy_003',
      buddyName: 'نور إبراهيم',
      buddyInitials: 'ن.إ',
      avatarColorKey: 'purple',
      university: 'الجامعة الأمريكية',
      availability: BuddyAvailability.online,
      topic: 'علم الأعصاب الإدراكي',
      subject: 'علم الأعصاب',
      description:
          'نقاش وقراءة جماعية في آخر أبحاث علم الأعصاب الإدراكي. الجلسة مفتوحة للطلاب الجادين في هذا المجال.',
      rules: [
        'احضر الورقة البحثية المطلوبة',
        'انجليزي أو عربي — كلاهما مقبول',
      ],
      gift: '🧠 ملخص مجاني للجلسة عبر البريد الإلكتروني',
      members: [
        BuddyMemberModel(
          id: 'm6', name: 'نور إبراهيم', initials: 'ن.إ', avatarColorKey: 'purple',
          university: 'الجامعة الأمريكية', studyField: 'علم الأعصاب',
          interests: ['أعصاب', 'علم النفس', 'بحث علمي'], rating: 4.7, totalSessions: 28, isFounder: true,
        ),
      ],
      maxCapacity: 5,
      startTime: DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day + 1, 11, 0),
      timeLabel: 'غداً • 11:00 ص',
      workspaceId: 'ws_001',
      workspaceName: 'مكتبة الجامعة الأمريكية',
      workspaceAddress: 'التجمع الخامس، القاهرة الجديدة',
      sessionStatus: BuddySessionStatus.open,
    ),
    BuddySessionModel(
      id: 'buddy_004',
      buddyName: 'أحمد سامي',
      buddyInitials: 'أ.س',
      avatarColorKey: 'green',
      university: 'جامعة القاهرة',
      availability: BuddyAvailability.online,
      topic: 'تدريب الكيمياء العضوية',
      subject: 'الكيمياء العضوية',
      description:
          'حل نماذج امتحانات سابقة في الكيمياء العضوية مع شرح التفاعلات بشكل تفصيلي. الجلسة مثالية للاستعداد للاختبارات.',
      rules: [
        'احضر نماذج الامتحانات',
        'أدوات رسم المعادلات ضرورية',
        'لا صوت أثناء الحل الفردي',
      ],
      gift: '🍵 شاي أو قهوة من المؤسس لكل المشاركين',
      members: [
        BuddyMemberModel(
          id: 'm7', name: 'أحمد سامي', initials: 'أ.س', avatarColorKey: 'green',
          university: 'جامعة القاهرة', studyField: 'كيمياء حيوية',
          interests: ['كيمياء', 'أحياء', 'طب'], rating: 4.5, totalSessions: 19, isFounder: true,
        ),
        BuddyMemberModel(
          id: 'm8', name: 'ريم طارق', initials: 'ر.ط', avatarColorKey: 'orange',
          university: 'جامعة القاهرة', studyField: 'صيدلة',
          interests: ['كيمياء', 'أدوية'], rating: 4.2, totalSessions: 7,
        ),
        BuddyMemberModel(
          id: 'm9', name: 'عمر فاروق', initials: 'ع.ف', avatarColorKey: 'blue',
          university: 'جامعة عين شمس', studyField: 'كيمياء تطبيقية',
          interests: ['كيمياء', 'مواد'], rating: 4.0, totalSessions: 5,
        ),
        BuddyMemberModel(
          id: 'm10', name: 'هنا علاء', initials: 'ه.ع', avatarColorKey: 'red',
          university: 'جامعة القاهرة', studyField: 'كيمياء',
          interests: ['كيمياء', 'رياضيات'], rating: 4.6, totalSessions: 13,
        ),
      ],
      maxCapacity: 4,
      startTime: DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day, 19, 0),
      timeLabel: 'اليوم • 7:00 م',
      workspaceId: 'ws_001',
      workspaceName: 'مكتبة الجامعة الأمريكية',
      workspaceAddress: 'التجمع الخامس، القاهرة الجديدة',
      sessionStatus: BuddySessionStatus.full,
    ),
  ];

  @override
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university, String? subject, String? filter,
  }) async {
    await Future.delayed(const Duration(milliseconds: 500));
    var results = List<BuddySessionModel>.from(_allSessions);
    if (university != null && university.isNotEmpty) {
      results = results.where((s) => s.university.contains(university)).toList();
    }
    if (subject != null && subject.isNotEmpty) {
      results = results.where((s) => s.subject.contains(subject)).toList();
    }
    if (filter == 'availableNow') {
      results = results.where((s) => s.availability == BuddyAvailability.online).toList();
    }
    if (filter == 'open') {
      results = results.where((s) => s.sessionStatus == BuddySessionStatus.open).toList();
    }
    return results;
  }

  @override
  Future<bool> joinSession(String sessionId) async {
    await Future.delayed(const Duration(milliseconds: 400));
    return true;
  }

  @override
  Future<bool> createSession(Map<String, dynamic> data) async {
    await Future.delayed(const Duration(milliseconds: 600));
    return true;
  }
}

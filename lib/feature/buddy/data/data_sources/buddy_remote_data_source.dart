import '../model/buddy_session_model.dart';
import '../../domain/entity/buddy_session_entity.dart';

abstract class BuddyRemoteDataSource {
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  });
}

/// ─── Dummy implementation ──────────────────────────────────────────────────
/// TODO: inject DioHelper + replace bodies with real API calls when ready.
/// ───────────────────────────────────────────────────────────────────────────
class BuddyRemoteDataSourceImpl implements BuddyRemoteDataSource {
  static const _allSessions = [
    BuddySessionModel(
      id: 'buddy_001',
      buddyName: 'سارة علي',
      buddyInitials: 'س.ع',
      avatarColorKey: 'red',
      university: 'جامعة القاهرة',
      subject: 'التفاضل والتكامل',
      timeLabel: 'اليوم • 3:00 م',
      availability: BuddyAvailability.online,
    ),
    BuddySessionModel(
      id: 'buddy_002',
      buddyName: 'محمد حسن',
      buddyInitials: 'م.ح',
      avatarColorKey: 'blue',
      university: 'جامعة عين شمس',
      subject: 'الفيزياء الحديثة',
      timeLabel: 'اليوم • 5:30 م',
      availability: BuddyAvailability.busy,
    ),
    BuddySessionModel(
      id: 'buddy_003',
      buddyName: 'نور إبراهيم',
      buddyInitials: 'ن.إ',
      avatarColorKey: 'purple',
      university: 'الجامعة الأمريكية',
      subject: 'علم الأعصاب',
      timeLabel: 'غداً • 11:00 ص',
      availability: BuddyAvailability.online,
    ),
    BuddySessionModel(
      id: 'buddy_004',
      buddyName: 'أحمد سامي',
      buddyInitials: 'أ.س',
      avatarColorKey: 'green',
      university: 'جامعة القاهرة',
      subject: 'الكيمياء العضوية',
      timeLabel: 'اليوم • 7:00 م',
      availability: BuddyAvailability.online,
    ),
  ];

  @override
  Future<List<BuddySessionModel>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    await Future.delayed(const Duration(milliseconds: 500));

    var results = List<BuddySessionModel>.from(_allSessions);

    if (university != null && university.isNotEmpty) {
      results = results
          .where((s) => s.university.contains(university))
          .toList();
    }
    if (subject != null && subject.isNotEmpty) {
      results = results
          .where((s) => s.subject.contains(subject))
          .toList();
    }
    if (filter == 'availableNow') {
      results = results
          .where((s) => s.availability == BuddyAvailability.online)
          .toList();
    }

    return results;
  }
}

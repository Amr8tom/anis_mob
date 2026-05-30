import '../../domain/entity/study_session_entity.dart';
import '../model/study_session_model.dart';
import '../model/user_profile_model.dart';

abstract class HomeRemoteDataSource {
  Future<UserProfileModel> getUserProfile();
  Future<List<StudySessionModel>> getTodaySessions();
}

/// ─── Dummy implementation ──────────────────────────────────────────────────
/// Returns hardcoded data. When real API is ready:
///   1. Inject DioHelper
///   2. Replace each method body with an actual HTTP call
///   3. No other layer needs to change.
/// ───────────────────────────────────────────────────────────────────────────
class HomeRemoteDataSourceImpl implements HomeRemoteDataSource {
  // TODO: inject DioHelper here when switching to real API
  // final DioHelper dioHelper;
  // HomeRemoteDataSourceImpl(this.dioHelper);

  @override
  Future<UserProfileModel> getUserProfile() async {
    // Simulate network delay
    await Future.delayed(const Duration(milliseconds: 600));
    return const UserProfileModel(
      id: 'dummy_001',
      name: 'عبد الله محمود',
      initials: 'ع.م',
      subscriptionType: 'silver',
      subscriptionDaysRemaining: 18,
      subscriptionTotalDays: 30,
      walletBalance: 1200,
      totalStudyHours: 124,
      streakDays: 7,
    );
  }

  @override
  Future<List<StudySessionModel>> getTodaySessions() async {
    await Future.delayed(const Duration(milliseconds: 600));
    return const [
      StudySessionModel(
        id: 'session_001',
        title: 'الفيزياء — الفصل 4',
        university: 'جامعة القاهرة',
        timeLabel: '2:00 م',
        tagLabel: 'فيز',
        tagColorKey: 'blue',
        status: SessionStatus.upcoming,
        participantCount: 6,
        maxParticipants: 10,
      ),
      StudySessionModel(
        id: 'session_002',
        title: 'التفاضل والتكامل',
        university: 'جامعة عين شمس',
        timeLabel: '4:30 م',
        tagLabel: 'ر',
        tagColorKey: 'yellow',
        status: SessionStatus.upcoming,
        participantCount: 4,
        maxParticipants: 8,
      ),
      StudySessionModel(
        id: 'session_003',
        title: 'علم النفس العام',
        university: 'الجامعة الأمريكية',
        timeLabel: '7:00 م',
        tagLabel: 'ن',
        tagColorKey: 'pink',
        status: SessionStatus.upcoming,
        participantCount: 9,
        maxParticipants: 10,
      ),
    ];
  }
}

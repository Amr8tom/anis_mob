import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entity/study_session_entity.dart';
import '../../domain/entity/user_profile_entity.dart';
import '../../domain/repository/home_repository.dart';

class HomeDummyRepository implements HomeRepository {
  @override
  Future<Either<Failure, UserProfileEntity>> getUserProfile() async {
    await Future.delayed(const Duration(milliseconds: 300));
    return const Right(
      UserProfileEntity(
        id: 'dummy_001',
        name: 'عبد الله محمود',
        initials: 'ع.م',
        subscriptionType: 'silver',
        subscriptionDaysRemaining: 8,
        subscriptionTotalDays: 8,
        walletBalance: 1200,
        totalStudyHours: 124,
        streakDays: 7,
      ),
    );
  }

  @override
  Future<Either<Failure, List<StudySessionEntity>>> getTodaySessions() async {
    await Future.delayed(const Duration(milliseconds: 300));
    return const Right([
      StudySessionEntity(
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
      StudySessionEntity(
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
    ]);
  }
}

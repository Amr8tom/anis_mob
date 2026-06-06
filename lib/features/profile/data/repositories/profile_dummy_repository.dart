import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entity/profile_entity.dart';
import '../../domain/repository/profile_repository.dart';

class ProfileDummyRepository implements ProfileRepository {
  @override
  Future<Either<Failure, ProfileEntity>> getProfile() async {
    await Future.delayed(const Duration(milliseconds: 300));
    return const Right(
      ProfileEntity(
        id: 'user_001',
        name: 'محمد أحمد الحسيني',
        initials: 'م.أ',
        university: 'جامعة القاهرة',
        subscriptionType: 'gold',
        subscriptionDaysRemaining: 18,
        totalStudyHours: 142,
        streakDays: 14,
        totalSessions: 37,
        badges: [
          ProfileBadge(id: 'b1', label: 'أسبوع متواصل', iconKey: 'streak'),
          ProfileBadge(id: 'b2', label: '100 ساعة', iconKey: 'hours'),
          ProfileBadge(id: 'b3', label: 'أفضل طالب', iconKey: 'top'),
        ],
      ),
    );
  }
}

import '../../domain/entity/profile_entity.dart';
import '../model/profile_model.dart';

abstract class ProfileRemoteDataSource {
  Future<ProfileModel> getProfile();
}

/// ─── Dummy implementation ──────────────────────────────────────────────────
/// TODO: inject DioHelper + replace body with real API call when ready.
class ProfileRemoteDataSourceImpl implements ProfileRemoteDataSource {
  @override
  Future<ProfileModel> getProfile() async {
    await Future.delayed(const Duration(milliseconds: 400));
    return const ProfileModel(
      id: 'user_001',
      name: 'محمد أحمد الحسيني',
      initials: 'م.أ',
      university: 'جامعة القاهرة',
      subscriptionType: 'gold',
      subscriptionDaysRemaining: 18,
      walletBalance: 1250.0,
      totalStudyHours: 142,
      streakDays: 14,
      totalSessions: 37,
      badges: [
        ProfileBadge(id: 'b1', label: 'أسبوع متواصل', iconKey: 'streak'),
        ProfileBadge(id: 'b2', label: '100 ساعة', iconKey: 'hours'),
        ProfileBadge(id: 'b3', label: 'أفضل طالب', iconKey: 'top'),
      ],
    );
  }
}

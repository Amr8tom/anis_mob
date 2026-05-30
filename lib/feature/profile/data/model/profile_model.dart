import '../../domain/entity/profile_entity.dart';

class ProfileModel extends ProfileEntity {
  const ProfileModel({
    required super.id,
    required super.name,
    required super.initials,
    required super.university,
    required super.subscriptionType,
    required super.subscriptionDaysRemaining,
    required super.walletBalance,
    required super.totalStudyHours,
    required super.streakDays,
    required super.totalSessions,
    required super.badges,
  });
}

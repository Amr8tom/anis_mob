import '../../domain/entity/user_profile_entity.dart';

class UserProfileModel extends UserProfileEntity {
  const UserProfileModel({
    required super.id,
    required super.name,
    required super.initials,
    required super.subscriptionType,
    required super.subscriptionDaysRemaining,
    required super.subscriptionTotalDays,
    required super.walletBalance,
    required super.totalStudyHours,
    required super.streakDays,
  });

  factory UserProfileModel.fromJson(Map<String, dynamic> json) {
    return UserProfileModel(
      id: json['id']?.toString() ?? '',
      name: json['name'] ?? '',
      initials: json['initials'] ?? '',
      subscriptionType: json['subscriptionType'] ?? 'free',
      subscriptionDaysRemaining:
          (json['subscriptionDaysRemaining'] as num?)?.toInt() ?? 0,
      subscriptionTotalDays:
          (json['subscriptionTotalDays'] as num?)?.toInt() ?? 30,
      walletBalance: (json['walletBalance'] as num?)?.toDouble() ?? 0.0,
      totalStudyHours: (json['totalStudyHours'] as num?)?.toInt() ?? 0,
      streakDays: (json['streakDays'] as num?)?.toInt() ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'initials': initials,
        'subscriptionType': subscriptionType,
        'subscriptionDaysRemaining': subscriptionDaysRemaining,
        'subscriptionTotalDays': subscriptionTotalDays,
        'walletBalance': walletBalance,
        'totalStudyHours': totalStudyHours,
        'streakDays': streakDays,
      };
}

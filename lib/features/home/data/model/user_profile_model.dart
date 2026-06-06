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
      subscriptionType:
          json['subscriptionType'] ?? json['subscription_type'] ?? 'free',
      subscriptionDaysRemaining:
          (json['subscriptionDaysRemaining'] as num?)?.toInt() ??
              (json['subscription_days_remaining'] as num?)?.toInt() ??
              0,
      subscriptionTotalDays: (json['subscriptionTotalDays'] as num?)?.toInt() ??
          (json['subscription_total_days'] as num?)?.toInt() ??
          (json['duration_days'] as num?)?.toInt() ??
          30,
      walletBalance: (json['walletBalance'] as num?)?.toDouble() ??
          (json['wallet_balance'] as num?)?.toDouble() ??
          0.0,
      totalStudyHours: (json['totalStudyHours'] as num?)?.toInt() ??
          (json['total_study_hours'] as num?)?.toInt() ??
          0,
      streakDays: (json['streakDays'] as num?)?.toInt() ??
          (json['streak_days'] as num?)?.toInt() ??
          0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'initials': initials,
        'subscriptionType': subscriptionType,
        'subscriptionDaysRemaining': subscriptionDaysRemaining,
        'subscriptionTotalDays': subscriptionTotalDays,
        'subscription_days_remaining': subscriptionDaysRemaining,
        'subscription_total_days': subscriptionTotalDays,
        'walletBalance': walletBalance,
        'totalStudyHours': totalStudyHours,
        'streakDays': streakDays,
      };
}

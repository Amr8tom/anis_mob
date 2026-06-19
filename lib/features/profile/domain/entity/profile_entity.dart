import 'package:equatable/equatable.dart';

class ProfileBadge extends Equatable {
  final String id;
  final String label;
  final String iconKey; // 'streak' | 'hours' | 'sessions' | 'top'
  const ProfileBadge({
    required this.id,
    required this.label,
    required this.iconKey,
  });

  @override
  List<Object?> get props => [id, label, iconKey];
}

class ProfileEntity extends Equatable {
  final String id;
  final String name;
  final String initials;
  final String email;
  final String university;
  final String studyField;
  final String gender;
  final List<String> interests;
  final String subscriptionType; // 'free' | 'silver' | 'gold'
  final int subscriptionDaysRemaining;
  final int subscriptionRemainingMinutes;
  final int subscriptionRemainingHours;
  final int totalStudyHours;
  final int streakDays;
  final int totalSessions;
  final List<ProfileBadge> badges;
  final String? avatarPath; // local file path for picked photo
  final bool profileCompleted;
  final int profileCompletionPercentage;
  final List<String> missingProfileFields;

  const ProfileEntity({
    required this.id,
    required this.name,
    required this.initials,
    this.email = '',
    required this.university,
    this.studyField = '',
    this.gender = '',
    this.interests = const [],
    required this.subscriptionType,
    required this.subscriptionDaysRemaining,
    required this.subscriptionRemainingMinutes,
    required this.subscriptionRemainingHours,
    required this.totalStudyHours,
    required this.streakDays,
    required this.totalSessions,
    required this.badges,
    this.avatarPath,
    this.profileCompleted = false,
    this.profileCompletionPercentage = 0,
    this.missingProfileFields = const [],
  });

  @override
  List<Object?> get props => [
        id,
        name,
        initials,
        email,
        university,
        studyField,
        gender,
        interests,
        subscriptionType,
        subscriptionDaysRemaining,
        subscriptionRemainingMinutes,
        subscriptionRemainingHours,
        totalStudyHours,
        streakDays,
        totalSessions,
        badges,
        avatarPath,
        profileCompleted,
        profileCompletionPercentage,
        missingProfileFields,
      ];
}

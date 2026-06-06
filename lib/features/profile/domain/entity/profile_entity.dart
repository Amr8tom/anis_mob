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
  final String university;
  final String subscriptionType; // 'free' | 'silver' | 'gold'
  final int subscriptionDaysRemaining;
  final int totalStudyHours;
  final int streakDays;
  final int totalSessions;
  final List<ProfileBadge> badges;
  final String? avatarPath; // local file path for picked photo

  const ProfileEntity({
    required this.id,
    required this.name,
    required this.initials,
    required this.university,
    required this.subscriptionType,
    required this.subscriptionDaysRemaining,
    required this.totalStudyHours,
    required this.streakDays,
    required this.totalSessions,
    required this.badges,
    this.avatarPath,
  });

  @override
  List<Object?> get props => [
        id, name, initials, university, subscriptionType,
        subscriptionDaysRemaining,
        totalStudyHours, streakDays, totalSessions, badges, avatarPath,
      ];
}

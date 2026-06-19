import 'package:equatable/equatable.dart';

class UserProfileEntity extends Equatable {
  final String id;
  final String name;
  final String initials;
  final String subscriptionType; // 'free' | 'silver' | 'gold'
  final int subscriptionDaysRemaining;
  final int subscriptionTotalDays;
  final int subscriptionRemainingMinutes;
  final int subscriptionRemainingHours;
  final int totalStudyHours;
  final int streakDays;

  const UserProfileEntity({
    required this.id,
    required this.name,
    required this.initials,
    required this.subscriptionType,
    required this.subscriptionDaysRemaining,
    required this.subscriptionTotalDays,
    required this.subscriptionRemainingMinutes,
    required this.subscriptionRemainingHours,
    required this.totalStudyHours,
    required this.streakDays,
  });

  /// Remaining subscription-days fraction for the plan length (0.0 - 1.0).
  double get subscriptionProgress => subscriptionTotalDays == 0
      ? 0
      : (subscriptionDaysRemaining / subscriptionTotalDays).clamp(0.0, 1.0);

  @override
  List<Object?> get props => [
        id,
        name,
        initials,
        subscriptionType,
        subscriptionDaysRemaining,
        subscriptionTotalDays,
        subscriptionRemainingMinutes,
        subscriptionRemainingHours,
        totalStudyHours,
        streakDays,
      ];
}

import 'package:equatable/equatable.dart';

/// Availability dot colour key
enum BuddyAvailability { online, busy, offline }

class BuddySessionEntity extends Equatable {
  final String id;
  final String buddyName;
  final String buddyInitials;
  /// One of: 'red' | 'blue' | 'purple' | 'green' — maps to avatar bg colour
  final String avatarColorKey;
  final String university;
  final String subject;
  /// Human-readable time label, e.g. "اليوم • 3:00 م"
  final String timeLabel;
  final BuddyAvailability availability;

  const BuddySessionEntity({
    required this.id,
    required this.buddyName,
    required this.buddyInitials,
    required this.avatarColorKey,
    required this.university,
    required this.subject,
    required this.timeLabel,
    required this.availability,
  });

  bool get isOnline => availability == BuddyAvailability.online;

  @override
  List<Object?> get props => [
        id,
        buddyName,
        buddyInitials,
        avatarColorKey,
        university,
        subject,
        timeLabel,
        availability,
      ];
}

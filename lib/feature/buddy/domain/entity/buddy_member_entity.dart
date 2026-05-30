import 'package:equatable/equatable.dart';

class BuddyMemberEntity extends Equatable {
  final String id;
  final String name;
  final String initials;
  final String avatarColorKey; // 'red' | 'blue' | 'purple' | 'green' | 'orange'
  final String university;
  final String studyField;
  final List<String> interests;
  final double rating; // 0.0 – 5.0
  final int totalSessions;
  final bool isFounder;

  const BuddyMemberEntity({
    required this.id,
    required this.name,
    required this.initials,
    required this.avatarColorKey,
    required this.university,
    required this.studyField,
    required this.interests,
    required this.rating,
    required this.totalSessions,
    this.isFounder = false,
  });

  @override
  List<Object?> get props => [
        id, name, initials, avatarColorKey, university,
        studyField, interests, rating, totalSessions, isFounder,
      ];
}

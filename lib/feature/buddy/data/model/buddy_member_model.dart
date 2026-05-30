import '../../domain/entity/buddy_member_entity.dart';

class BuddyMemberModel extends BuddyMemberEntity {
  const BuddyMemberModel({
    required super.id,
    required super.name,
    required super.initials,
    required super.avatarColorKey,
    required super.university,
    required super.studyField,
    required super.interests,
    required super.rating,
    required super.totalSessions,
    super.isFounder,
  });
}

import '../../domain/entity/buddy_session_entity.dart';

class BuddySessionModel extends BuddySessionEntity {
  const BuddySessionModel({
    required super.id,
    required super.buddyName,
    required super.buddyInitials,
    required super.avatarColorKey,
    required super.university,
    required super.availability,
    required super.topic,
    required super.subject,
    required super.description,
    super.rules,
    super.gift,
    super.members,
    super.maxCapacity,
    required super.startTime,
    required super.timeLabel,
    required super.workspaceId,
    required super.workspaceName,
    required super.workspaceAddress,
    super.sessionStatus,
  });
}

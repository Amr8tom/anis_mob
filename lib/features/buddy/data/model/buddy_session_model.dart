import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/entity/buddy_member_entity.dart';
import 'buddy_member_model.dart';
import '../../../workspaces/data/model/workspace_model.dart';
import '../../../workspaces/domain/entity/workspace_entity.dart';

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
    required super.workspace,
    super.sessionStatus,
  });

  factory BuddySessionModel.fromJson(Map<String, dynamic> json) {
    return BuddySessionModel(
      id: json['id']?.toString() ?? '',
      buddyName: json['buddyName'] as String? ?? '',
      buddyInitials: json['buddyInitials'] as String? ?? '',
      avatarColorKey: json['avatarColorKey'] as String? ?? 'blue',
      university: json['university'] as String? ?? '',
      availability: _parseAvailability(json['availability']),
      topic: json['topic'] as String? ?? '',
      subject: json['subject'] as String? ?? '',
      description: json['description'] as String? ?? '',
      rules: (json['rules'] as List<dynamic>? ?? const [])
          .map((item) => '$item')
          .toList(),
      gift: json['gift'] as String?,
      members: (json['members'] as List<dynamic>? ?? const [])
          .map((item) => BuddyMemberModel.fromJson(
                item as Map<String, dynamic>,
              ))
          .toList(),
      maxCapacity: (json['maxCapacity'] as num?)?.toInt() ?? 6,
      startTime: DateTime.tryParse(json['startTime'] as String? ?? '') ??
          DateTime.now(),
      timeLabel: json['timeLabel'] as String? ?? '',
      workspace: WorkspaceModel.fromJson(
        json['workspace'] as Map<String, dynamic>? ?? const {},
      ),
      sessionStatus: _parseStatus(json['sessionStatus']),
    );
  }

  Map<String, dynamic> toJson() => toJsonFromEntity(this);

  static Map<String, dynamic> toJsonFromEntity(BuddySessionEntity entity) => {
        'id': entity.id,
        'buddyName': entity.buddyName,
        'buddyInitials': entity.buddyInitials,
        'avatarColorKey': entity.avatarColorKey,
        'university': entity.university,
        'availability': entity.availability.name,
        'topic': entity.topic,
        'subject': entity.subject,
        'description': entity.description,
        'rules': entity.rules,
        'gift': entity.gift,
        'members': entity.members.map(_memberToJson).toList(),
        'maxCapacity': entity.maxCapacity,
        'startTime': entity.startTime.toIso8601String(),
        'timeLabel': entity.timeLabel,
        'workspace': _workspaceToJson(entity.workspace),
        'sessionStatus': entity.sessionStatus.name,
      };

  static Map<String, dynamic> _memberToJson(BuddyMemberEntity entity) {
    return BuddyMemberModel.toJsonFromEntity(entity);
  }

  static Map<String, dynamic> _workspaceToJson(WorkspaceEntity entity) {
    if (entity is WorkspaceModel) return entity.toJson();
    return WorkspaceModel(
      id: entity.id,
      name: entity.name,
      address: entity.address,
      description: entity.description,
      latitude: entity.latitude,
      longitude: entity.longitude,
      galleryImages: entity.galleryImages,
      drinks: entity.drinks,
      currentOccupancy: entity.currentOccupancy,
      capacity: entity.capacity,
      status: entity.status,
      distanceKm: entity.distanceKm,
      openTime: entity.openTime,
      closeTime: entity.closeTime,
      amenities: entity.amenities,
      sessions: entity.sessions,
    ).toJson();
  }

  static BuddyAvailability _parseAvailability(dynamic raw) {
    switch (raw?.toString()) {
      case 'busy':
        return BuddyAvailability.busy;
      case 'offline':
        return BuddyAvailability.offline;
      default:
        return BuddyAvailability.online;
    }
  }

  static BuddySessionStatus _parseStatus(dynamic raw) {
    switch (raw?.toString()) {
      case 'full':
        return BuddySessionStatus.full;
      case 'inProgress':
        return BuddySessionStatus.inProgress;
      default:
        return BuddySessionStatus.open;
    }
  }
}

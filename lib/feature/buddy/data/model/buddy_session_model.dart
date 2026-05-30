import '../../domain/entity/buddy_session_entity.dart';

class BuddySessionModel extends BuddySessionEntity {
  const BuddySessionModel({
    required super.id,
    required super.buddyName,
    required super.buddyInitials,
    required super.avatarColorKey,
    required super.university,
    required super.subject,
    required super.timeLabel,
    required super.availability,
  });

  factory BuddySessionModel.fromJson(Map<String, dynamic> json) {
    return BuddySessionModel(
      id: json['id']?.toString() ?? '',
      buddyName: json['buddyName'] ?? '',
      buddyInitials: json['buddyInitials'] ?? '',
      avatarColorKey: json['avatarColorKey'] ?? 'blue',
      university: json['university'] ?? '',
      subject: json['subject'] ?? '',
      timeLabel: json['timeLabel'] ?? '',
      availability: _parseAvailability(json['availability']),
    );
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

  Map<String, dynamic> toJson() => {
        'id': id,
        'buddyName': buddyName,
        'buddyInitials': buddyInitials,
        'avatarColorKey': avatarColorKey,
        'university': university,
        'subject': subject,
        'timeLabel': timeLabel,
        'availability': availability.name,
      };
}

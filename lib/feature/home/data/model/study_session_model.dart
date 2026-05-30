import '../../domain/entity/study_session_entity.dart';

class StudySessionModel extends StudySessionEntity {
  const StudySessionModel({
    required super.id,
    required super.title,
    required super.university,
    required super.timeLabel,
    required super.tagLabel,
    required super.tagColorKey,
    required super.status,
    required super.participantCount,
    required super.maxParticipants,
  });

  factory StudySessionModel.fromJson(Map<String, dynamic> json) {
    return StudySessionModel(
      id: json['id']?.toString() ?? '',
      title: json['title'] ?? '',
      university: json['university'] ?? '',
      timeLabel: json['timeLabel'] ?? '',
      tagLabel: json['tagLabel'] ?? '',
      tagColorKey: json['tagColorKey'] ?? 'blue',
      status: _parseStatus(json['status']),
      participantCount: (json['participantCount'] as num?)?.toInt() ?? 0,
      maxParticipants: (json['maxParticipants'] as num?)?.toInt() ?? 10,
    );
  }

  static SessionStatus _parseStatus(dynamic raw) {
    switch (raw?.toString()) {
      case 'inProgress':
        return SessionStatus.inProgress;
      case 'ended':
        return SessionStatus.ended;
      default:
        return SessionStatus.upcoming;
    }
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'university': university,
        'timeLabel': timeLabel,
        'tagLabel': tagLabel,
        'tagColorKey': tagColorKey,
        'status': status.name,
        'participantCount': participantCount,
        'maxParticipants': maxParticipants,
      };
}

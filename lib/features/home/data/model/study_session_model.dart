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

  Map<String, dynamic> toJson() => toJsonFromEntity(this);

  static Map<String, dynamic> toJsonFromEntity(StudySessionEntity entity) => {
        'id': entity.id,
        'title': entity.title,
        'university': entity.university,
        'timeLabel': entity.timeLabel,
        'tagLabel': entity.tagLabel,
        'tagColorKey': entity.tagColorKey,
        'status': entity.status.name,
        'participantCount': entity.participantCount,
        'maxParticipants': entity.maxParticipants,
      };
}

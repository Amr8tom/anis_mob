import 'package:equatable/equatable.dart';

/// Status of a study session
enum SessionStatus { upcoming, inProgress, ended }

class StudySessionEntity extends Equatable {
  final String id;
  final String title;        // e.g. "الفيزياء — الفصل 4"
  final String university;   // e.g. "جامعة القاهرة"
  final String timeLabel;    // e.g. "2:00 م"
  final String tagLabel;     // short tag shown in avatar, e.g. "فيز"
  /// One of: 'blue' | 'yellow' | 'pink' | 'green'
  final String tagColorKey;
  final SessionStatus status;
  final int participantCount;
  final int maxParticipants;

  const StudySessionEntity({
    required this.id,
    required this.title,
    required this.university,
    required this.timeLabel,
    required this.tagLabel,
    required this.tagColorKey,
    required this.status,
    required this.participantCount,
    required this.maxParticipants,
  });

  bool get isFull => participantCount >= maxParticipants;

  @override
  List<Object?> get props => [
        id,
        title,
        university,
        timeLabel,
        tagLabel,
        tagColorKey,
        status,
        participantCount,
        maxParticipants,
      ];
}

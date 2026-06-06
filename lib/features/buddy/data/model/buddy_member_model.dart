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

  factory BuddyMemberModel.fromJson(Map<String, dynamic> json) {
    return BuddyMemberModel(
      id: json['id']?.toString() ?? '',
      name: json['name'] as String? ?? '',
      initials: json['initials'] as String? ?? '',
      avatarColorKey: json['avatarColorKey'] as String? ?? 'blue',
      university: json['university'] as String? ?? '',
      studyField: json['studyField'] as String? ?? '',
      interests: (json['interests'] as List<dynamic>? ?? const [])
          .map((item) => '$item')
          .toList(),
      rating: (json['rating'] as num?)?.toDouble() ?? 0,
      totalSessions: (json['totalSessions'] as num?)?.toInt() ?? 0,
      isFounder: json['isFounder'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => toJsonFromEntity(this);

  static Map<String, dynamic> toJsonFromEntity(BuddyMemberEntity entity) => {
        'id': entity.id,
        'name': entity.name,
        'initials': entity.initials,
        'avatarColorKey': entity.avatarColorKey,
        'university': entity.university,
        'studyField': entity.studyField,
        'interests': entity.interests,
        'rating': entity.rating,
        'totalSessions': entity.totalSessions,
        'isFounder': entity.isFounder,
      };
}

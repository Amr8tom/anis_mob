import '../../domain/entity/profile_entity.dart';

class ProfileModel extends ProfileEntity {
  const ProfileModel({
    required super.id,
    required super.name,
    required super.initials,
    super.email,
    required super.university,
    super.studyField,
    super.gender,
    super.interests,
    required super.subscriptionType,
    required super.subscriptionDaysRemaining,
    required super.totalStudyHours,
    required super.streakDays,
    required super.totalSessions,
    required super.badges,
    super.avatarPath,
    super.profileCompleted,
    super.profileCompletionPercentage,
    super.missingProfileFields,
  });

  factory ProfileModel.fromJson(Map<String, dynamic> json) {
    final badgesJson = json['badges'] as List<dynamic>? ?? const [];
    return ProfileModel(
      id: json['id']?.toString() ?? '',
      name: json['name'] as String? ?? '',
      initials: json['initials'] as String? ?? '',
      email: json['email'] as String? ?? '',
      university: json['university'] as String? ?? '',
      studyField: json['studyField'] as String? ?? '',
      gender: json['gender'] as String? ?? '',
      interests: _stringList(json['interests']),
      subscriptionType: json['subscriptionType'] as String? ?? 'free',
      subscriptionDaysRemaining:
          (json['subscriptionDaysRemaining'] as num?)?.toInt() ?? 0,
      totalStudyHours: (json['totalStudyHours'] as num?)?.toInt() ?? 0,
      streakDays: (json['streakDays'] as num?)?.toInt() ?? 0,
      totalSessions: (json['totalSessions'] as num?)?.toInt() ?? 0,
      badges: badgesJson
          .map((item) => ProfileBadgeModel.fromJson(
                item as Map<String, dynamic>,
              ))
          .toList(),
      avatarPath: json['avatarPath'] as String?,
      profileCompleted: json['profileCompleted'] as bool? ?? false,
      profileCompletionPercentage:
          (json['profileCompletionPercentage'] as num?)?.toInt() ?? 0,
      missingProfileFields: _stringList(json['missingProfileFields']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'initials': initials,
        'email': email,
        'university': university,
        'studyField': studyField,
        'gender': gender,
        'interests': interests,
        'subscriptionType': subscriptionType,
        'subscriptionDaysRemaining': subscriptionDaysRemaining,
        'totalStudyHours': totalStudyHours,
        'streakDays': streakDays,
        'totalSessions': totalSessions,
        'badges': badges.map(ProfileBadgeModel.toJsonFromEntity).toList(),
        'avatarPath': avatarPath,
        'profileCompleted': profileCompleted,
        'profileCompletionPercentage': profileCompletionPercentage,
        'missingProfileFields': missingProfileFields,
      };

  static List<String> _stringList(dynamic value) =>
      (value as List<dynamic>? ?? const []).map((item) => '$item').toList();
}

class ProfileBadgeModel extends ProfileBadge {
  const ProfileBadgeModel({
    required super.id,
    required super.label,
    required super.iconKey,
  });

  factory ProfileBadgeModel.fromJson(Map<String, dynamic> json) {
    return ProfileBadgeModel(
      id: json['id']?.toString() ?? '',
      label: json['label'] as String? ?? '',
      iconKey: json['iconKey'] as String? ?? '',
    );
  }

  static Map<String, dynamic> toJsonFromEntity(ProfileBadge entity) => {
        'id': entity.id,
        'label': entity.label,
        'iconKey': entity.iconKey,
      };
}

import '../../domain/entity/workspace_entity.dart';
import '../../../home/data/model/study_session_model.dart';
import '../../domain/entity/workspace_drink_entity.dart';

class WorkspaceModel extends WorkspaceEntity {
  const WorkspaceModel({
    required super.id,
    required super.name,
    required super.address,
    super.description,
    super.latitude,
    super.longitude,
    super.galleryImages,
    super.drinks,
    required super.currentOccupancy,
    required super.capacity,
    required super.status,
    required super.distanceKm,
    required super.openTime,
    required super.closeTime,
    super.dayCalculationHours,
    super.hourMultiplier,
    required super.amenities,
    super.sessions,
  });

  factory WorkspaceModel.fromJson(Map<String, dynamic> json) {
    return WorkspaceModel(
      id: json['id']?.toString() ?? '',
      name: json['name'] as String? ?? '',
      address: json['address'] as String? ?? '',
      description: json['description'] as String? ?? '',
      latitude: (json['latitude'] as num?)?.toDouble() ?? 0,
      longitude: (json['longitude'] as num?)?.toDouble() ?? 0,
      galleryImages: _stringList(json['galleryImages']),
      drinks: (json['drinks'] as List<dynamic>? ?? const [])
          .map((item) => WorkspaceDrinkModel.fromJson(
                item as Map<String, dynamic>,
              ))
          .toList(),
      currentOccupancy: (json['currentOccupancy'] as num?)?.toInt() ?? 0,
      capacity: (json['capacity'] as num?)?.toInt() ?? 0,
      status: _parseStatus(json['status']),
      distanceKm: (json['distanceKm'] as num?)?.toDouble() ?? 0,
      openTime: json['openTime'] as String? ?? '',
      closeTime: json['closeTime'] as String? ?? '',
      dayCalculationHours: (json['dayCalculationHours'] as num?)?.toInt() ??
          (json['day_calculation_hours'] as num?)?.toInt() ??
          8,
      hourMultiplier: (json['hourMultiplier'] as num?)?.toDouble() ??
          (json['hour_multiplier'] as num?)?.toDouble() ??
          1.0,
      amenities: _stringList(json['amenities']),
      sessions: (json['sessions'] as List<dynamic>? ?? const [])
          .map((item) => StudySessionModel.fromJson(
                item as Map<String, dynamic>,
              ))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'address': address,
        'description': description,
        'latitude': latitude,
        'longitude': longitude,
        'galleryImages': galleryImages,
        'drinks': drinks.map(WorkspaceDrinkModel.toJsonFromEntity).toList(),
        'currentOccupancy': currentOccupancy,
        'capacity': capacity,
        'status': status.name,
        'distanceKm': distanceKm,
        'openTime': openTime,
        'closeTime': closeTime,
        'dayCalculationHours': dayCalculationHours,
        'day_calculation_hours': dayCalculationHours,
        'hourMultiplier': hourMultiplier,
        'hour_multiplier': hourMultiplier,
        'amenities': amenities,
        'sessions': sessions
            .map((item) => StudySessionModel.toJsonFromEntity(item))
            .toList(),
      };

  static List<String> _stringList(dynamic value) =>
      (value as List<dynamic>? ?? const []).map((item) => '$item').toList();

  static WorkspaceStatus _parseStatus(dynamic raw) {
    switch (raw?.toString()) {
      case 'busy':
        return WorkspaceStatus.busy;
      case 'full':
        return WorkspaceStatus.full;
      case 'closed':
        return WorkspaceStatus.closed;
      default:
        return WorkspaceStatus.open;
    }
  }
}

class WorkspaceDrinkModel extends WorkspaceDrinkEntity {
  const WorkspaceDrinkModel({
    required super.id,
    required super.name,
    required super.icon,
    required super.price,
  });

  factory WorkspaceDrinkModel.fromJson(Map<String, dynamic> json) {
    return WorkspaceDrinkModel(
      id: json['id']?.toString() ?? '',
      name: json['name'] as String? ?? '',
      icon: json['icon'] as String? ?? '',
      price: (json['price'] as num?)?.toDouble() ?? 0,
    );
  }

  static Map<String, dynamic> toJsonFromEntity(WorkspaceDrinkEntity entity) => {
        'id': entity.id,
        'name': entity.name,
        'icon': entity.icon,
        'price': entity.price,
      };
}

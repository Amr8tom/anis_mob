import '../../domain/entity/workspace_entity.dart';

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
    required super.amenities,
  });
}

import '../../domain/entity/workspace_entity.dart';

class WorkspaceModel extends WorkspaceEntity {
  const WorkspaceModel({
    required super.id,
    required super.name,
    required super.address,
    required super.currentOccupancy,
    required super.capacity,
    required super.status,
    required super.distanceKm,
    required super.openTime,
    required super.closeTime,
    required super.amenities,
  });
}

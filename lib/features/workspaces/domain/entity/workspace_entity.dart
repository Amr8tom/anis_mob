import 'package:equatable/equatable.dart';

import '../../../home/domain/entity/study_session_entity.dart';
import 'workspace_drink_entity.dart';

enum WorkspaceStatus { open, busy, full, closed }

class WorkspaceEntity extends Equatable {
  final String id;
  final String name;
  final String address;
  final String description;
  final double latitude;
  final double longitude;
  final List<String> galleryImages;
  final List<WorkspaceDrinkEntity> drinks;

  /// Current number of students checked in
  final int currentOccupancy;
  final int capacity;
  final WorkspaceStatus status;

  /// Distance in km from user location
  final double distanceKm;

  /// Human-readable open time, e.g. "8:00 ص"
  final String openTime;

  /// Human-readable close time, e.g. "11:00 م"
  final String closeTime;

  /// Minimum study hours that count as one subscription day.
  final int dayCalculationHours;

  /// Amenity icon keys: 'wifi' | 'ac' | 'coffee' | 'printing' | 'quiet'
  final List<String> amenities;

  /// Sessions currently happening or upcoming in this workspace
  final List<StudySessionEntity> sessions;

  const WorkspaceEntity({
    required this.id,
    required this.name,
    required this.address,
    this.description = '',
    this.latitude = 0,
    this.longitude = 0,
    this.galleryImages = const [],
    this.drinks = const [],
    required this.currentOccupancy,
    required this.capacity,
    required this.status,
    required this.distanceKm,
    required this.openTime,
    required this.closeTime,
    this.dayCalculationHours = 8,
    required this.amenities,
    this.sessions = const [],
  });

  /// 0.0 – 1.0 fill fraction
  double get occupancyFraction =>
      capacity == 0 ? 0 : (currentOccupancy / capacity).clamp(0.0, 1.0);

  bool get isOpen => status == WorkspaceStatus.open;

  @override
  List<Object?> get props => [
        id,
        name,
        address,
        currentOccupancy,
        capacity,
        description,
        latitude,
        longitude,
        galleryImages,
        drinks,
        status,
        distanceKm,
        openTime,
        closeTime,
        dayCalculationHours,
        amenities,
        sessions,
      ];
}

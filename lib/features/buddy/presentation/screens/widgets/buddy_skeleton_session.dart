import '../../../domain/entity/buddy_session_entity.dart';
import '../../../../workspaces/domain/entity/workspace_entity.dart';

BuddySessionEntity buddySkeletonSession(int index) {
  const names = ['████ ████', '███ ████', '████ █████'];
  const initials = ['ر.ع', 'م.ح', 'ن.إ'];
  const universities = ['███████████████', '████████████', '█████████████'];
  const keys = ['blue', 'red', 'purple'];
  final now = DateTime.now();

  return BuddySessionEntity(
    id: 'sk_$index',
    buddyName: names[index % 3],
    buddyInitials: initials[index % 3],
    avatarColorKey: keys[index % 3],
    university: universities[index % 3],
    availability: BuddyAvailability.online,
    topic: '████████████████',
    subject: '████████████',
    description: '████████████████████████████',
    startTime: now,
    timeLabel: '██████',
    workspace: const WorkspaceEntity(
      id: '',
      name: '██████████',
      address: '█████████████',
      currentOccupancy: 0,
      capacity: 0,
      status: WorkspaceStatus.open,
      distanceKm: 0,
      openTime: '',
      closeTime: '',
      amenities: [],
    ),
  );
}

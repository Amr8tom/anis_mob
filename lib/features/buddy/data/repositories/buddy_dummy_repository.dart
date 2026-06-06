import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../workspaces/domain/entity/workspace_entity.dart';
import '../../domain/entity/buddy_member_entity.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/repository/buddy_repository.dart';

class BuddyDummyRepository implements BuddyRepository {
  static const _workspace = WorkspaceEntity(
    id: 'ws_001',
    name: 'مكتبة القاهرة المركزية',
    address: 'وسط البلد، القاهرة',
    currentOccupancy: 28,
    capacity: 40,
    status: WorkspaceStatus.open,
    distanceKm: 1.2,
    openTime: '8:00 ص',
    closeTime: '11:00 م',
    amenities: ['wifi', 'ac', 'quiet'],
  );

  static final _sessions = [
    BuddySessionEntity(
      id: 'buddy_001',
      buddyName: 'سارة علي',
      buddyInitials: 'س.ع',
      avatarColorKey: 'red',
      university: 'جامعة القاهرة',
      availability: BuddyAvailability.online,
      topic: 'حل مسائل التفاضل والتكامل',
      subject: 'التفاضل والتكامل',
      description: 'جلسة دراسة جماعية لحل مسائل التفاضل والتكامل.',
      rules: const ['أحضر كتبك ومعادلاتك', 'لا للهاتف أثناء الجلسة'],
      gift: '☕ فنجان قهوة مجاني لكل مشارك',
      members: const [
        BuddyMemberEntity(
          id: 'm1',
          name: 'سارة علي',
          initials: 'س.ع',
          avatarColorKey: 'red',
          university: 'جامعة القاهرة',
          studyField: 'هندسة رياضية',
          interests: ['رياضيات', 'فيزياء', 'برمجة'],
          rating: 4.8,
          totalSessions: 23,
          isFounder: true,
        ),
      ],
      maxCapacity: 6,
      startTime: DateTime(2026, 6, 4, 15),
      timeLabel: 'اليوم • 3:00 م',
      workspace: _workspace,
    ),
  ];

  @override
  Future<Either<Failure, List<BuddySessionEntity>>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    await Future.delayed(const Duration(milliseconds: 300));
    var result = List<BuddySessionEntity>.from(_sessions);
    if (university != null && university.isNotEmpty) {
      result =
          result.where((item) => item.university.contains(university)).toList();
    }
    if (subject != null && subject.isNotEmpty) {
      result = result.where((item) => item.subject.contains(subject)).toList();
    }
    if (filter == 'availableNow') {
      result = result
          .where((item) => item.availability == BuddyAvailability.online)
          .toList();
    }
    if (filter == 'open') {
      result = result
          .where((item) => item.sessionStatus == BuddySessionStatus.open)
          .toList();
    }
    return Right(result);
  }

  @override
  Future<Either<Failure, BuddySessionEntity>> getBuddySessionDetails(
    String sessionId,
  ) async {
    try {
      return Right(_sessions.firstWhere((session) => session.id == sessionId));
    } on StateError {
      return const Left(NotFoundFailure(message: 'Session not found'));
    }
  }

  @override
  Future<Either<Failure, bool>> joinSession(String sessionId) async {
    await Future.delayed(const Duration(milliseconds: 250));
    return const Right(true);
  }

  @override
  Future<Either<Failure, bool>> createSession(Map<String, dynamic> data) async {
    await Future.delayed(const Duration(milliseconds: 250));
    return const Right(true);
  }
}

import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/buddy_session_entity.dart';

abstract class BuddyRepository {
  Future<Either<Failure, List<BuddySessionEntity>>> getBuddySessions({
    String? university,
    String? subject,
    String? filter, // 'all' | 'today' | 'thisWeek' | 'availableNow'
  });
}

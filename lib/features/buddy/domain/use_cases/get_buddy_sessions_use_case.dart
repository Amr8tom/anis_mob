import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../entity/buddy_session_entity.dart';
import '../repository/buddy_repository.dart';

class GetBuddySessionsUseCase {
  final BuddyRepository repository;

  GetBuddySessionsUseCase(this.repository);

  Future<Either<Failure, List<BuddySessionEntity>>> call({
    required BuddySessionsParams params,
  }) {
    return repository.getBuddySessions(
      university: params.university,
      subject: params.subject,
      filter: params.filter,
    );
  }
}

class BuddySessionsParams extends Equatable {
  final String? university;
  final String? subject;
  final String filter; // 'all' | 'today' | 'thisWeek' | 'availableNow'

  const BuddySessionsParams({
    this.university,
    this.subject,
    this.filter = 'all',
  });

  @override
  List<Object?> get props => [university, subject, filter];
}

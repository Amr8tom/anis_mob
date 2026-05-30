import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entity/buddy_session_entity.dart';
import '../../domain/repository/buddy_repository.dart';
import '../data_sources/buddy_remote_data_source.dart';

class BuddyRepositoryImpl implements BuddyRepository {
  final BuddyRemoteDataSource remoteDataSource;

  BuddyRepositoryImpl({required this.remoteDataSource});

  @override
  Future<Either<Failure, List<BuddySessionEntity>>> getBuddySessions({
    String? university,
    String? subject,
    String? filter,
  }) async {
    try {
      final result = await remoteDataSource.getBuddySessions(
        university: university,
        subject: subject,
        filter: filter,
      );
      return Right(result);
    } catch (e) {
      return Left(ServerFailure(message: e.toString()));
    }
  }
}

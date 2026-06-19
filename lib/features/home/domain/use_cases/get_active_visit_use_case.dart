import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_attendance_entity.dart';
import '../repository/workspace_attendance_repository.dart';

/// Fetches the user's currently active visit (null if not checked in).
/// Used to detect when an owner approves a pending checkout request.
class GetActiveVisitUseCase {
  final WorkspaceAttendanceRepository _repository;
  const GetActiveVisitUseCase(this._repository);

  Future<Either<Failure, WorkspaceAttendanceEntity?>> call() {
    return _repository.getActiveVisit();
  }
}

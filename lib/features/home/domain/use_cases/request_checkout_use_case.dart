import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_attendance_entity.dart';
import '../repository/workspace_attendance_repository.dart';

class RequestCheckoutParams {
  final String attendanceId;

  const RequestCheckoutParams({required this.attendanceId});
}

/// Ask the workspace owner to check the user out (approval-mode workspaces).
/// For free visits / direct-mode workspaces the server checks out immediately.
class RequestCheckoutUseCase {
  final WorkspaceAttendanceRepository _repository;
  const RequestCheckoutUseCase(this._repository);

  Future<Either<Failure, WorkspaceAttendanceEntity>> call({
    required RequestCheckoutParams params,
  }) {
    return _repository.requestCheckout(attendanceId: params.attendanceId);
  }
}

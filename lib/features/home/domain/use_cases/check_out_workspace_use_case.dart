import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_attendance_entity.dart';
import '../repository/workspace_attendance_repository.dart';

class CheckOutWorkspaceParams {
  final String attendanceId;
  final String workspaceId;
  final String workspaceName;
  final DateTime checkInTime;
  final Duration elapsedTime;

  const CheckOutWorkspaceParams({
    required this.attendanceId,
    required this.workspaceId,
    required this.workspaceName,
    required this.checkInTime,
    required this.elapsedTime,
  });
}

class CheckOutWorkspaceUseCase {
  final WorkspaceAttendanceRepository _repository;
  const CheckOutWorkspaceUseCase(this._repository);

  Future<Either<Failure, WorkspaceAttendanceEntity>> call({
    required CheckOutWorkspaceParams params,
  }) {
    return _repository.checkOut(
      attendanceId: params.attendanceId,
      workspaceId: params.workspaceId,
      workspaceName: params.workspaceName,
      checkInTime: params.checkInTime,
      elapsedTime: params.elapsedTime,
    );
  }
}

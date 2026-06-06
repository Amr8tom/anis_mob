import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entity/workspace_attendance_entity.dart';
import '../repository/workspace_attendance_repository.dart';

class CheckInWorkspaceParams {
  final String qrPayload;
  final DateTime checkInTime;

  const CheckInWorkspaceParams({
    required this.qrPayload,
    required this.checkInTime,
  });
}

class CheckInWorkspaceUseCase {
  final WorkspaceAttendanceRepository _repository;
  const CheckInWorkspaceUseCase(this._repository);

  Future<Either<Failure, WorkspaceAttendanceEntity>> call({
    required CheckInWorkspaceParams params,
  }) {
    return _repository.checkIn(
      qrPayload: params.qrPayload,
      checkInTime: params.checkInTime,
    );
  }
}

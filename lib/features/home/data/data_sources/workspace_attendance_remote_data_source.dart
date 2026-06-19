import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../model/workspace_attendance_model.dart';

abstract class WorkspaceAttendanceRemoteDataSource {
  Future<WorkspaceAttendanceModel> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  });

  Future<WorkspaceAttendanceModel> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  });

  Future<WorkspaceAttendanceModel> requestCheckout({
    required String attendanceId,
  });

  /// Returns the active visit, or null when the user is not checked in.
  Future<WorkspaceAttendanceModel?> getActiveVisit();
}

class WorkspaceAttendanceRemoteDataSourceImpl implements WorkspaceAttendanceRemoteDataSource {
  final DioHelper _dio;

  const WorkspaceAttendanceRemoteDataSourceImpl(this._dio);

  @override
  Future<WorkspaceAttendanceModel> checkIn({
    required String qrPayload,
    required DateTime checkInTime,
  }) async {
    final response = await _dio.post(
      url: URL.checkIn,
      data: {'qr_payload': qrPayload},
      requiresAuth: true,
    );
    return WorkspaceAttendanceModel.fromJson(_extractObject(response));
  }

  @override
  Future<WorkspaceAttendanceModel> checkOut({
    required String attendanceId,
    required String workspaceId,
    required String workspaceName,
    required DateTime checkInTime,
    required Duration elapsedTime,
  }) async {
    final response = await _dio.post(
      url: URL.checkOut(attendanceId),
      requiresAuth: true,
    );
    return WorkspaceAttendanceModel.fromJson(_extractObject(response));
  }

  @override
  Future<WorkspaceAttendanceModel> requestCheckout({
    required String attendanceId,
  }) async {
    final response = await _dio.post(
      url: URL.requestCheckout(attendanceId),
      requiresAuth: true,
    );
    return WorkspaceAttendanceModel.fromJson(_extractObject(response));
  }

  @override
  Future<WorkspaceAttendanceModel?> getActiveVisit() async {
    final response = await _dio.get(
      url: URL.activeVisit,
      requiresAuth: true,
    );
    final data = response is Map<String, dynamic> ? response['data'] : null;
    if (data is Map<String, dynamic>) {
      return WorkspaceAttendanceModel.fromJson(data);
    }
    // data is null → the user has no active visit.
    return null;
  }

  Map<String, dynamic> _extractObject(dynamic response) {
    if (response is Map<String, dynamic>) {
      final data = response['data'];
      if (data is Map<String, dynamic>) return data;
    }
    throw const ServerFailure(
      message: 'Unexpected attendance response shape',
    );
  }
}

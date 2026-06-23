import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../domain/use_cases/register_device_token_use_case.dart';
import '../../domain/use_cases/unregister_device_token_use_case.dart';

abstract class NotificationsRemoteDataSource {
  Future<void> registerDeviceToken(RegisterDeviceTokenParams params);
  Future<void> unregisterDeviceToken(UnregisterDeviceTokenParams params);
}

class NotificationsRemoteDataSourceImpl
    implements NotificationsRemoteDataSource {
  final DioHelper _dio;

  const NotificationsRemoteDataSourceImpl(this._dio);

  @override
  Future<void> registerDeviceToken(RegisterDeviceTokenParams params) async {
    // Authenticated endpoint: the Bearer token is attached by DioHelper.
    // Any error surfaces as a typed Failure thrown by DioHelper.
    await _dio.post(
      url: URL.deviceTokens,
      data: params.toMap(),
      requiresAuth: true,
    );
  }

  @override
  Future<void> unregisterDeviceToken(UnregisterDeviceTokenParams params) async {
    await _dio.delete(
      url: URL.deviceTokens,
      data: params.toMap(),
      requiresAuth: true,
    );
  }
}

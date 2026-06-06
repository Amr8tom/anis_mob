import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../domain/use_cases/create_user_use_case.dart';
import '../../domain/use_cases/login_user_use_case.dart';
import '../models/create_use_info_model.dart';
import '../models/login_model.dart';

abstract class AuthRemoteDataSources {
  Future<LoginModel> loginUser({required LoginUserParams params});
  Future<CreateUseInfoModel> createUserInfo({
    required CreateUserInfoParams params,
  });
  Future<void> signOut();
}

class AuthRemoteDataSourcesImpl implements AuthRemoteDataSources {
  final DioHelper _dio;

  const AuthRemoteDataSourcesImpl(this._dio);

  @override
  Future<LoginModel> loginUser({required LoginUserParams params}) async {
    // Public endpoint: no Bearer token is sent. Any error surfaces as a typed
    // Failure thrown by DioHelper — no try/catch needed here.
    final response = await _dio.post(
      url: URL.login,
      data: params.toMap(),
      requiresAuth: false,
    );
    return LoginModel.fromJson(_asMap(response));
  }

  @override
  Future<CreateUseInfoModel> createUserInfo({
    required CreateUserInfoParams params,
  }) async {
    // Public endpoint: no Bearer token is sent.
    final response = await _dio.post(
      url: URL.createInfo,
      data: params.toMap(),
      requiresAuth: false,
    );
    return CreateUseInfoModel.fromJson(_asMap(response));
  }

  @override
  Future<void> signOut() async {
    // Authenticated endpoint: revokes the current Sanctum token server-side.
    await _dio.post(url: URL.logout, requiresAuth: true);
  }

  Map<String, dynamic> _asMap(dynamic response) {
    return response is Map<String, dynamic> ? response : <String, dynamic>{};
  }
}

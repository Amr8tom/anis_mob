import '../../../../core/constants/api_constants.dart';
import '../../../../core/dio/dio_helper.dart';
import '../../../../core/error/failure.dart';
import '../../domain/use_cases/create_user_use_case.dart';
import '../../domain/use_cases/login_user_use_case.dart';
import '../models/create_use_info_model.dart';
import '../models/login_model.dart';

abstract class AuthRemoteDataSources {
  Future<LoginModel> loginUser({required LoginUserParams params});
  Future<CreateUseInfoModel> createUserInfo(
      {required CreateUserInfoParams params});
  Future<void> signOut();
}

class AuthRemoteDataSourcesImpl implements AuthRemoteDataSources {
  final DioHelper _dio;
  const AuthRemoteDataSourcesImpl(this._dio);

  @override
  Future<LoginModel> loginUser({required LoginUserParams params}) async {
    try {
      final response =
          await _dio.postData(url: URL.login, body: params.toMap());
      if (response == null) {
        throw const ServerFailure(message: 'Server failure');
      }
      return LoginModel.fromJson(response);
    } on ServerFailure catch (e) {
      throw ServerFailure(message: e.message);
    }
  }

  @override
  Future<CreateUseInfoModel> createUserInfo(
      {required CreateUserInfoParams params}) async {
    try {
      final response =
          await _dio.postData(url: URL.createInfo, body: params.toMap());
      if (response == null) {
        throw const ServerFailure(message: 'Server failure');
      }
      return CreateUseInfoModel.fromJson(response);
    } on ServerFailure catch (e) {
      throw ServerFailure(message: e.message);
    }
  }

  @override
  Future<void> signOut() async {}
}

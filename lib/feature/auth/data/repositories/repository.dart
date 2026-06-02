import 'package:dartz/dartz.dart';

import '../../../../core/connection/checkNetwork.dart';
import '../../../../core/error/failure.dart';
import '../../domain/entities/create_user_info.dart';
import '../../domain/entities/login.dart';
import '../../domain/repositories/auth_repository.dart';
import '../../domain/use_cases/create_user_use_case.dart';
import '../../domain/use_cases/login_user_use_case.dart';
import '../data_sources/local_data_sources.dart';
import '../data_sources/remote_data_sources.dart';

class AuthRepositoryImp extends AuthRepository {
  final AuthRemoteDataSources _remoteDataSource;
  final AuthLocalDataSources _localDataSource;
  final NetworkInfo _networkInfo;

  AuthRepositoryImp(this._remoteDataSource, this._localDataSource, this._networkInfo);

  @override
  Future<Either<Failure, Login>> loginUser({required LoginUserParams params}) async {
    if (await _networkInfo.isConnected) {
      try {
        final result = await _remoteDataSource.loginUser(params: params);
        return Right(result);
      } on ServerFailure catch (e) {
        return Left(ServerFailure(message: e.message));
      }
    }
    return Left(CacheFailure());
  }

  @override
  Future<Either<Failure, CreateUserInfo>> createUserInfo({required CreateUserInfoParams params}) async {
    if (await _networkInfo.isConnected) {
      try {
        final result = await _remoteDataSource.createUserInfo(params: params);
        return Right(result);
      } on ServerFailure catch (e) {
        return Left(ServerFailure(message: e.message));
      }
    }
    return Left(CacheFailure());
  }

  @override
  Future<void> signOut() async {}
}

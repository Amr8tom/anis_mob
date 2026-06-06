import 'package:dartz/dartz.dart';

import '../../../../core/connection/check_network.dart';
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

  AuthRepositoryImp(
      this._remoteDataSource, this._localDataSource, this._networkInfo);

  @override
  Future<Either<Failure, Login>> loginUser(
      {required LoginUserParams params}) async {
    if (await _networkInfo.isConnected) {
      try {
        final result = await _remoteDataSource.loginUser(params: params);
        if (result.token != null && result.token!.isNotEmpty) {
          await _localDataSource.saveToken(result.token!);
          await _localDataSource.clearGuestFlag();
        }
        return Right(result);
      } on Failure catch (failure) {
        // Preserve the typed failure (Validation/Unauthorized/…) as-is.
        return Left(failure);
      }
    }
    return Left(const CacheFailure());
  }

  @override
  Future<Either<Failure, CreateUserInfo>> createUserInfo(
      {required CreateUserInfoParams params}) async {
    if (await _networkInfo.isConnected) {
      try {
        final result = await _remoteDataSource.createUserInfo(params: params);
        if (result.token != null && result.token!.isNotEmpty) {
          await _localDataSource.saveToken(result.token!);
          await _localDataSource.clearGuestFlag();
        }
        return Right(result);
      } on Failure catch (failure) {
        return Left(failure);
      }
    }
    return Left(const CacheFailure());
  }

  @override
  Future<Either<Failure, void>> loginAsGuest() async {
    try {
      await _localDataSource.saveGuestFlag();
      return const Right(null);
    } catch (_) {
      return Left(CacheFailure());
    }
  }

  @override
  Future<Either<Failure, String?>> getToken() async {
    try {
      return Right(await _localDataSource.getToken());
    } catch (_) {
      return Left(CacheFailure());
    }
  }

  @override
  Future<Either<Failure, bool>> isGuest() async {
    try {
      return Right(await _localDataSource.isGuest());
    } catch (_) {
      return Left(CacheFailure());
    }
  }

  @override
  Future<Either<Failure, void>> signOut() async {
    try {
      // Best-effort server-side token revocation; local sign-out must always
      // succeed even if the network call fails.
      if (await _networkInfo.isConnected) {
        try {
          await _remoteDataSource.signOut();
        } on Failure catch (_) {
          // Ignore remote logout errors — proceed to clear local session.
        }
      }
      await _localDataSource.clearToken();
      await _localDataSource.clearGuestFlag();
      return const Right(null);
    } catch (_) {
      return Left(const CacheFailure());
    }
  }

  @override
  Future<Either<Failure, void>> cacheUserInfoDraft({
    required String name,
    required String email,
    required String whatsAppNumber,
    required String gender,
    required String avatar,
    required String university,
    required String major,
    required String year,
  }) async {
    try {
      await _localDataSource.saveUserInfoDraft(
        name: name,
        email: email,
        whatsAppNumber: whatsAppNumber,
        gender: gender,
        avatar: avatar,
        university: university,
        major: major,
        year: year,
      );
      return const Right(null);
    } catch (_) {
      return Left(CacheFailure());
    }
  }
}

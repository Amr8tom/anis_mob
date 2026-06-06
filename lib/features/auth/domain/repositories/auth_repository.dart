import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entities/create_user_info.dart';
import '../entities/login.dart';
import '../use_cases/create_user_use_case.dart';
import '../use_cases/login_user_use_case.dart';

abstract class AuthRepository {
  Future<Either<Failure, Login>> loginUser({required LoginUserParams params});
  Future<Either<Failure, CreateUserInfo>> createUserInfo(
      {required CreateUserInfoParams params});
  Future<Either<Failure, String?>> getToken();
  Future<Either<Failure, bool>> isGuest();
  Future<Either<Failure, void>> loginAsGuest();
  Future<Either<Failure, void>> cacheUserInfoDraft({
    required String name,
    required String email,
    required String whatsAppNumber,
    required String gender,
    required String avatar,
    required String university,
    required String major,
    required String year,
  });
  Future<Either<Failure, void>> signOut();
}

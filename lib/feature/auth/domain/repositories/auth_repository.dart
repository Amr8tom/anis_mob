import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entities/create_user_info.dart';
import '../entities/login.dart';
import '../use_cases/create_user_use_case.dart';
import '../use_cases/login_user_use_case.dart';

abstract class AuthRepository {
  Future<Either<Failure, Login>> loginUser({required LoginUserParams params});
  Future<Either<Failure, CreateUserInfo>> createUserInfo({required CreateUserInfoParams params});
  Future<void> signOut();
}

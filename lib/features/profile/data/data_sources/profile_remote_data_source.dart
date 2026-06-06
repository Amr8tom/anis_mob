import '../model/profile_model.dart';

abstract class ProfileRemoteDataSource {
  Future<ProfileModel> getProfile();
}

class ProfileRemoteDataSourceImpl implements ProfileRemoteDataSource {
  @override
  Future<ProfileModel> getProfile() {
    throw UnimplementedError('Profile API is not wired yet.');
  }
}

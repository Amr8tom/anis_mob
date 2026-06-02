abstract class AuthLocalDataSources {
  Future<String?> getToken();

  Future<void> saveToken(String token);

  Future<void> clearToken();
}

class AuthLocalDataSourcesImpl implements AuthLocalDataSources {
  @override
  Future<String?> getToken() async => null;

  @override
  Future<void> saveToken(String token) async {}

  @override
  Future<void> clearToken() async {}
}
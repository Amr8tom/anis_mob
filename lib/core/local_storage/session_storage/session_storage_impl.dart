import '../local_storage.dart';
import '../storage_keys.dart';
import 'session_storage.dart';

class SessionStorageImpl implements SessionStorage {
  final LocalStorage storage;

  const SessionStorageImpl(this.storage);

  @override
  String? get token => storage.getString(key: StorageKeys.token.name);

  @override
  String? get userId => storage.getString(key: StorageKeys.userId.name);

  @override
  String? get employeeId => storage.getString(key: StorageKeys.employeeId.name);

  @override
  String? get managerId => storage.getString(key: StorageKeys.managerId.name);

  @override
  String? get userName => storage.getString(key: StorageKeys.userName.name);

  @override
  String? get organizationName =>
      storage.getString(key: StorageKeys.organizationName.name);

  @override
  String? get departmentAddress =>
      storage.getString(key: StorageKeys.departmentAddress.name);

  @override
  String? get offices => storage.getString(key: StorageKeys.offices.name);

  @override
  String? get officesList =>
      storage.getString(key: StorageKeys.officesList.name);

  @override
  Future<void> saveToken(String value) =>
      storage.cacheString(key: StorageKeys.token.name, value: value);

  @override
  Future<void> saveUserId(String value) =>
      storage.cacheString(key: StorageKeys.userId.name, value: value);

  @override
  Future<void> saveEmployeeId(String value) =>
      storage.cacheString(key: StorageKeys.employeeId.name, value: value);

  @override
  Future<void> saveManagerId(String value) =>
      storage.cacheString(key: StorageKeys.managerId.name, value: value);

  @override
  Future<void> saveUserName(String value) =>
      storage.cacheString(key: StorageKeys.userName.name, value: value);

  @override
  Future<void> saveOrganizationName(String value) =>
      storage.cacheString(key: StorageKeys.organizationName.name, value: value);

  @override
  Future<void> saveDepartmentAddress(String value) => storage.cacheString(
      key: StorageKeys.departmentAddress.name, value: value);

  @override
  Future<void> saveOffices(String value) =>
      storage.cacheString(key: StorageKeys.offices.name, value: value);

  @override
  Future<void> saveOfficesList(String value) =>
      storage.cacheString(key: StorageKeys.officesList.name, value: value);

  @override
  Future<void> clearSession() async {
    await storage.remove(key: StorageKeys.token.name);
    await storage.remove(key: StorageKeys.userId.name);
    await storage.remove(key: StorageKeys.employeeId.name);
    await storage.remove(key: StorageKeys.managerId.name);
    await storage.remove(key: StorageKeys.userName.name);
    await storage.remove(key: StorageKeys.organizationName.name);
    await storage.remove(key: StorageKeys.departmentAddress.name);
    await storage.remove(key: StorageKeys.offices.name);
    await storage.remove(key: StorageKeys.officesList.name);
  }
}

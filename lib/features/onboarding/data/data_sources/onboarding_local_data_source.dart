import '../../../../core/local_storage/local_storage.dart';
import '../../../../core/local_storage/storage_keys.dart';

abstract class OnboardingLocalDataSource {
  Future<bool> hasCompletedOnboarding();

  Future<void> completeOnboarding();
}

class OnboardingLocalDataSourceImpl implements OnboardingLocalDataSource {
  final LocalStorage storage;

  const OnboardingLocalDataSourceImpl(this.storage);

  @override
  Future<bool> hasCompletedOnboarding() async {
    return storage.getBool(key: StorageKeys.onboardingCompleted.name);
  }

  @override
  Future<void> completeOnboarding() async {
    await storage.cacheBool(
      key: StorageKeys.onboardingCompleted.name,
      value: true,
    );
  }
}

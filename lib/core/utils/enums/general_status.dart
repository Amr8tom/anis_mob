enum GeneralStatus { initialized, loading, success, error }

extension GeneralStatusExtension on GeneralStatus {
  bool get isInitialized => this == GeneralStatus.initialized;

  bool get isLoading => this == GeneralStatus.loading;

  bool get isSuccess => this == GeneralStatus.success;

  bool get isError => this == GeneralStatus.error;
}

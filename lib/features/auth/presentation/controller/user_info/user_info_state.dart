part of 'user_info_cubit.dart';

@immutable
final class UserInfoState extends Equatable {
  final GeneralStatus status;

  // ── Step 0 — Account ────────────────────────────────────
  final String name;
  final String phone;
  final String whatsAppNumber;

  // ── Wizard state ─────────────────────────────────────────
  final int step;
  final String stepError;
  final String errorMessage;

  const UserInfoState({
    this.status = GeneralStatus.initialized,
    this.name = '',
    this.phone = '',
    this.whatsAppNumber = '',
    this.step = 0,
    this.stepError = '',
    this.errorMessage = '',
  });

  UserInfoState copyWith({
    GeneralStatus? status,
    String? name,
    String? phone,
    String? whatsAppNumber,
    int? step,
    String? stepError,
    String? errorMessage,
  }) {
    return UserInfoState(
      status: status ?? this.status,
      name: name ?? this.name,
      phone: phone ?? this.phone,
      whatsAppNumber: whatsAppNumber ?? this.whatsAppNumber,
      step: step ?? this.step,
      stepError: stepError ?? this.stepError,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [
        status,
        name,
        phone,
        whatsAppNumber,
        step,
        stepError,
        errorMessage,
      ];
}

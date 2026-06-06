part of 'user_info_cubit.dart';

@immutable
final class UserInfoState extends Equatable {
  final GeneralStatus status;

  // ── Step 0 — Account ────────────────────────────────────
  final String name;
  final String email;
  final String phone;
  final String whatsAppNumber;

  // ── Step 1 — Profile ────────────────────────────────────
  final int? genderId; // 1 = male, 2 = female
  final String specialization; // free-text field

  // ── Wizard state ─────────────────────────────────────────
  final int step;
  final String stepError;
  final String errorMessage;

  const UserInfoState({
    this.status = GeneralStatus.initialized,
    this.name = '',
    this.email = '',
    this.phone = '',
    this.whatsAppNumber = '',
    this.genderId,
    this.specialization = '',
    this.step = 0,
    this.stepError = '',
    this.errorMessage = '',
  });

  UserInfoState copyWith({
    GeneralStatus? status,
    String? name,
    String? email,
    String? phone,
    String? whatsAppNumber,
    int? genderId,
    String? specialization,
    int? step,
    String? stepError,
    String? errorMessage,
  }) {
    return UserInfoState(
      status: status ?? this.status,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      whatsAppNumber: whatsAppNumber ?? this.whatsAppNumber,
      genderId: genderId ?? this.genderId,
      specialization: specialization ?? this.specialization,
      step: step ?? this.step,
      stepError: stepError ?? this.stepError,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }

  @override
  List<Object?> get props => [
        status,
        name,
        email,
        phone,
        whatsAppNumber,
        genderId,
        specialization,
        step,
        stepError,
        errorMessage,
      ];
}

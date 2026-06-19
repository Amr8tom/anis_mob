import 'package:equatable/equatable.dart';

class SubscriptionActivationEntity extends Equatable {
  final String id;
  final String scope;
  final String? workspaceName;
  final String? planName;
  final int? remainingMinutes;
  final String message;

  const SubscriptionActivationEntity({
    required this.id,
    required this.scope,
    this.workspaceName,
    this.planName,
    this.remainingMinutes,
    required this.message,
  });

  @override
  List<Object?> get props =>
      [id, scope, workspaceName, planName, remainingMinutes, message];
}

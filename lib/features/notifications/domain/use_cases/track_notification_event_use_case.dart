import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../repositories/notifications_repository.dart';

class TrackNotificationEventUseCase
    extends UseCase<Unit, TrackNotificationEventParams> {
  final NotificationsRepository repository;

  TrackNotificationEventUseCase(this.repository);

  @override
  Future<Either<Failure, Unit>> call({
    required TrackNotificationEventParams params,
  }) {
    return repository.trackNotificationEvent(params: params);
  }
}

class TrackNotificationEventParams {
  final String event;
  final String campaignId;
  final String? recipientId;

  const TrackNotificationEventParams({
    required this.event,
    required this.campaignId,
    this.recipientId,
  });

  Map<String, dynamic> toMap() {
    return {
      'event': event,
      'campaign_id': campaignId,
      if (recipientId != null && recipientId!.isNotEmpty)
        'recipient_id': recipientId,
    };
  }
}

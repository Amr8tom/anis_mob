import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/utils/usecases/base_usecase.dart';
import '../entities/notification_preferences_entity.dart';
import '../repositories/notifications_repository.dart';

class GetNotificationPreferencesUseCase
    extends UseCase<NotificationPreferencesEntity, NoParams> {
  final NotificationsRepository repository;

  GetNotificationPreferencesUseCase(this.repository);

  @override
  Future<Either<Failure, NotificationPreferencesEntity>> call({
    required NoParams params,
  }) {
    return repository.getNotificationPreferences();
  }
}

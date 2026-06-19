import 'package:dartz/dartz.dart';

import '../../../../core/constants/asset_resoures.dart';
import '../../../../core/error/failure.dart';
import '../../../home/domain/entity/study_session_entity.dart';
import '../../domain/entity/workspace_drink_entity.dart';
import '../../domain/entity/workspace_entity.dart';
import '../../domain/repository/workspace_repository.dart';

class WorkspaceDummyRepository implements WorkspaceRepository {
  static const _workspaces = [
    WorkspaceEntity(
      id: 'ws_001',
      name: 'مكتبة القاهرة المركزية',
      address: 'وسط البلد، القاهرة',
      description:
          'مساحة هادئة مناسبة للمذاكرة والعمل الجماعي مع إطلالة مريحة.',
      latitude: 30.0444,
      longitude: 31.2357,
      galleryImages: [AssetRes.product, AssetRes.webApps, AssetRes.mobileApps],
      drinks: [
        WorkspaceDrinkEntity(
          id: 'd1',
          name: 'Espresso',
          icon: 'assets/images/pngs/dollarIcon.png',
          price: 35,
        ),
      ],
      currentOccupancy: 28,
      capacity: 40,
      status: WorkspaceStatus.open,
      distanceKm: 1.2,
      openTime: '8:00 ص',
      closeTime: '11:00 م',
      dayCalculationHours: 8,
      hourMultiplier: 1.0,
      amenities: ['wifi', 'ac', 'quiet'],
      sessions: [
        StudySessionEntity(
          id: 'ss_001',
          title: 'الفيزياء — الفصل 4',
          university: 'جامعة القاهرة',
          timeLabel: '2:00 م',
          tagLabel: 'فيز',
          tagColorKey: 'blue',
          status: SessionStatus.inProgress,
          participantCount: 6,
          maxParticipants: 10,
        ),
      ],
    ),
    WorkspaceEntity(
      id: 'ws_002',
      name: 'مساحة بريدج الدراسية',
      address: 'مدينة نصر، القاهرة',
      description: 'مكان مناسب للمراجعة السريعة والاجتماعات الصغيرة.',
      latitude: 30.0647,
      longitude: 31.3386,
      galleryImages: [AssetRes.product, AssetRes.mobileApps],
      drinks: [
        WorkspaceDrinkEntity(
          id: 'd2',
          name: 'Mocha',
          icon: 'assets/images/pngs/dollarIcon.png',
          price: 55,
        ),
      ],
      currentOccupancy: 18,
      capacity: 20,
      status: WorkspaceStatus.busy,
      distanceKm: 2.8,
      openTime: '9:00 ص',
      closeTime: '10:00 م',
      dayCalculationHours: 6,
      hourMultiplier: 2.0,
      amenities: ['wifi', 'coffee', 'printing'],
    ),
  ];

  @override
  Future<Either<Failure, List<WorkspaceEntity>>> getWorkspaces({
    String? filter,
    double? latitude,
    double? longitude,
  }) async {
    await Future.delayed(const Duration(milliseconds: 300));
    var result = List<WorkspaceEntity>.from(_workspaces);
    if (filter == 'openNow') {
      result =
          result.where((item) => item.status == WorkspaceStatus.open).toList();
    } else if (filter == 'nearby') {
      result = result..sort((a, b) => a.distanceKm.compareTo(b.distanceKm));
    }
    return Right(result);
  }

  @override
  Future<Either<Failure, WorkspaceEntity>> getWorkspaceDetails(
    String workspaceId,
  ) async {
    try {
      return Right(
          _workspaces.firstWhere((workspace) => workspace.id == workspaceId));
    } on StateError {
      return const Left(NotFoundFailure(message: 'Workspace not found'));
    }
  }

}

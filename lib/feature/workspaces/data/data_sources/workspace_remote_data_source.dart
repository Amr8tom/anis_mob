import '../../domain/entity/workspace_entity.dart';
import '../model/workspace_model.dart';

abstract class WorkspaceRemoteDataSource {
  Future<List<WorkspaceModel>> getWorkspaces({String? filter});
}

/// ─── Dummy implementation ──────────────────────────────────────────────────
/// TODO: inject DioHelper + replace bodies with real API calls when ready.
class WorkspaceRemoteDataSourceImpl implements WorkspaceRemoteDataSource {
  static const _all = [
    WorkspaceModel(
      id: 'ws_001',
      name: 'مكتبة القاهرة المركزية',
      address: 'وسط البلد، القاهرة',
      currentOccupancy: 28,
      capacity: 40,
      status: WorkspaceStatus.open,
      distanceKm: 1.2,
      openTime: '8:00 ص',
      closeTime: '11:00 م',
      amenities: ['wifi', 'ac', 'quiet'],
    ),
    WorkspaceModel(
      id: 'ws_002',
      name: 'مساحة بريدج الدراسية',
      address: 'مدينة نصر، القاهرة',
      currentOccupancy: 18,
      capacity: 20,
      status: WorkspaceStatus.busy,
      distanceKm: 2.8,
      openTime: '9:00 ص',
      closeTime: '10:00 م',
      amenities: ['wifi', 'coffee', 'printing'],
    ),
    WorkspaceModel(
      id: 'ws_003',
      name: 'ستاديوم ستادي هاب',
      address: 'الزمالك، القاهرة',
      currentOccupancy: 35,
      capacity: 35,
      status: WorkspaceStatus.full,
      distanceKm: 3.5,
      openTime: '8:00 ص',
      closeTime: '12:00 م',
      amenities: ['wifi', 'ac', 'coffee', 'quiet'],
    ),
    WorkspaceModel(
      id: 'ws_004',
      name: 'مكتبة جامعة عين شمس',
      address: 'عين شمس، القاهرة',
      currentOccupancy: 12,
      capacity: 60,
      status: WorkspaceStatus.open,
      distanceKm: 5.1,
      openTime: '7:00 ص',
      closeTime: '9:00 م',
      amenities: ['wifi', 'quiet', 'printing'],
    ),
    WorkspaceModel(
      id: 'ws_005',
      name: 'كافيه ريد ستاديوم',
      address: 'المعادي، القاهرة',
      currentOccupancy: 0,
      capacity: 25,
      status: WorkspaceStatus.closed,
      distanceKm: 7.3,
      openTime: '10:00 ص',
      closeTime: '8:00 م',
      amenities: ['wifi', 'coffee'],
    ),
  ];

  @override
  Future<List<WorkspaceModel>> getWorkspaces({String? filter}) async {
    await Future.delayed(const Duration(milliseconds: 500));
    var results = List<WorkspaceModel>.from(_all);
    if (filter == 'openNow') {
      results = results
          .where((w) => w.status == WorkspaceStatus.open)
          .toList();
    } else if (filter == 'nearby') {
      results = results
        ..sort((a, b) => a.distanceKm.compareTo(b.distanceKm));
    }
    return results;
  }
}

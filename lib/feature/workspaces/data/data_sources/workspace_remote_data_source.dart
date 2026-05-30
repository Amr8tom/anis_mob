import '../../domain/entity/workspace_drink_entity.dart';
import '../../../../core/constants/asset_resoures.dart';
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
      description: 'مساحة هادئة مناسبة للمذاكرة والعمل الجماعي مع إطلالة مريحة.',
      latitude: 30.0444,
      longitude: 31.2357,
      galleryImages: const [
        AssetRes.product,
        AssetRes.webApps,
        AssetRes.mobileApps,
      ],
      drinks: const [
        WorkspaceDrinkEntity(id: 'd1', name: 'Espresso', icon: 'assets/images/pngs/dollarIcon.png', price: 35),
        WorkspaceDrinkEntity(id: 'd2', name: 'Cappuccino', icon: 'assets/images/pngs/dollarIcon.png', price: 48),
        WorkspaceDrinkEntity(id: 'd3', name: 'Latte', icon: 'assets/images/pngs/dollarIcon.png', price: 52),
      ],
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
      description: 'مكان مناسب للمراجعة السريعة والاجتماعات الصغيرة.',
      latitude: 30.0647,
      longitude: 31.3386,
      galleryImages: const [
        AssetRes.product,
        AssetRes.mobileApps,
      ],
      drinks: const [
        WorkspaceDrinkEntity(id: 'd1', name: 'Americano', icon: 'assets/images/pngs/dollarIcon.png', price: 30),
        WorkspaceDrinkEntity(id: 'd2', name: 'Mocha', icon: 'assets/images/pngs/dollarIcon.png', price: 55),
      ],
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
      description: 'مساحة عمل فاخرة وهادئة مع مرافق متكاملة.',
      latitude: 30.0676,
      longitude: 31.2218,
      galleryImages: const [
        AssetRes.webApps,
        AssetRes.product,
      ],
      drinks: const [
        WorkspaceDrinkEntity(id: 'd1', name: 'Tea', icon: 'assets/images/pngs/dollarIcon.png', price: 20),
        WorkspaceDrinkEntity(id: 'd2', name: 'Hot Chocolate', icon: 'assets/images/pngs/dollarIcon.png', price: 45),
      ],
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
      description: 'مكتبة واسعة مع أماكن متعددة للقراءة والمذاكرة.',
      latitude: 30.0903,
      longitude: 31.3194,
      galleryImages: const [
        AssetRes.mobileApps,
      ],
      drinks: const [
        WorkspaceDrinkEntity(id: 'd1', name: 'Cold Brew', icon: 'assets/images/pngs/dollarIcon.png', price: 40),
      ],
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
      description: 'كافيه هادئ مع إضاءة مريحة وطاولات فردية.',
      latitude: 29.9597,
      longitude: 31.2569,
      galleryImages: const [
        AssetRes.product,
      ],
      drinks: const [
        WorkspaceDrinkEntity(id: 'd1', name: 'Orange Juice', icon: 'assets/images/pngs/dollarIcon.png', price: 28),
        WorkspaceDrinkEntity(id: 'd2', name: 'Iced Coffee', icon: 'assets/images/pngs/dollarIcon.png', price: 42),
      ],
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

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:geolocator/geolocator.dart';

import '../../../../common/widgets/sizeboxs/sizer.dart';
import '../../../../core/constants/app_sizes.dart';
import '../../../../core/constants/colors.dart';
import '../../../../core/utils/helpers/location_helper.dart';
import '../../../../generated/l10n.dart';
import '../../../workspaces/presentation/controller/workspace_cubit.dart';
import '../controller/home_cubit.dart';

class PredefinedRegion {
  final String name;
  final double latitude;
  final double longitude;

  const PredefinedRegion({
    required this.name,
    required this.latitude,
    required this.longitude,
  });
}

class LocationSelectorBottomSheet extends StatefulWidget {
  const LocationSelectorBottomSheet({super.key});

  static Future<void> show(BuildContext context) {
    return showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => BlocProvider.value(
        value: context.read<HomeCubit>(),
        child: BlocProvider.value(
          value: context.read<WorkspaceCubit>(),
          child: const LocationSelectorBottomSheet(),
        ),
      ),
    );
  }

  @override
  State<LocationSelectorBottomSheet> createState() => _LocationSelectorBottomSheetState();
}

class _LocationSelectorBottomSheetState extends State<LocationSelectorBottomSheet> {
  bool _isLoadingGps = false;

  final List<PredefinedRegion> _regions = const [
    PredefinedRegion(
      name: 'الدقي، الجيزة',
      latitude: 30.0381,
      longitude: 31.2118,
    ),
    PredefinedRegion(
      name: 'التجمع الخامس، القاهرة',
      latitude: 30.0263,
      longitude: 31.4913,
    ),
    PredefinedRegion(
      name: 'مدينة نصر، القاهرة',
      latitude: 30.0571,
      longitude: 31.3415,
    ),
    PredefinedRegion(
      name: '٦ أكتوبر، الجيزة',
      latitude: 29.9734,
      longitude: 30.9481,
    ),
    PredefinedRegion(
      name: 'سموحة، الإسكندرية',
      latitude: 31.2089,
      longitude: 29.9556,
    ),
  ];

  Future<void> _handleGpsSelection() async {
    setState(() {
      _isLoadingGps = true;
    });

    try {
      final position = await LocationHelper.determinePosition();
      final address = await LocationHelper.getAddressFromLatLng(
        position.latitude,
        position.longitude,
      );

      if (!mounted) return;
      final homeCubit = context.read<HomeCubit>();
      final workspaceCubit = context.read<WorkspaceCubit>();
      final navigator = Navigator.of(context);

      await homeCubit.updateUserLocation(
        position.latitude,
        position.longitude,
        address,
      );

      workspaceCubit.loadWorkspaces();
      navigator.pop();
    } on LocationServiceDisabledException {
      _showError(S.current.please);
    } catch (e) {
      final errStr = e.toString();
      if (errStr.contains('denied') && errStr.contains('permanently')) {
        _showError(S.current.loc);
      } else if (errStr.contains('denied')) {
        _showError(S.current.locSer);
      } else {
        _showError(S.current.locationError);
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoadingGps = false;
        });
      }
    }
  }

  void _handleRegionSelection(PredefinedRegion region) async {
    final homeCubit = context.read<HomeCubit>();
    final workspaceCubit = context.read<WorkspaceCubit>();
    final navigator = Navigator.of(context);

    await homeCubit.updateUserLocation(
      region.latitude,
      region.longitude,
      region.name,
    );
    workspaceCubit.loadWorkspaces();
    navigator.pop();
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                color: ColorRes.white,
                fontFamily: 'Cairo',
              ),
        ),
        backgroundColor: ColorRes.anisErrorRed,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return Container(
      decoration: BoxDecoration(
        color: ColorRes.white,
        borderRadius: BorderRadius.only(
          topLeft: Radius.circular(24.r),
          topRight: Radius.circular(24.r),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.15),
            blurRadius: 10,
            spreadRadius: 2,
          ),
        ],
      ),
      padding: EdgeInsets.only(
        top: 14.h,
        left: AppSizes.padding,
        right: AppSizes.padding,
        bottom: MediaQuery.of(context).padding.bottom + AppSizes.padding,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Drag handle notch
          Center(
            child: Container(
              width: 45.w,
              height: 4.h,
              decoration: BoxDecoration(
                color: ColorRes.anisInputBorder,
                borderRadius: BorderRadius.circular(10),
              ),
            ),
          ),
          const Sizer(height: 18),

          // Title
          Text(
            'تحديد موقعك',
            textAlign: TextAlign.center,
            style: tt.headlineSmall?.copyWith(
              color: ColorRes.anisNavy,
              fontWeight: FontWeight.w800,
              fontFamily: 'Cairo',
            ),
          ),
          const Sizer(height: 8),
          Text(
            'اختر موقعك لنظهر لك المساحات الأقرب إليك ونحسب المسافات بدقة',
            textAlign: TextAlign.center,
            style: tt.bodySmall?.copyWith(
              color: ColorRes.anisTextMuted,
              fontFamily: 'Cairo',
            ),
          ),
          const Sizer(height: 20),

          // GPS Button
          ElevatedButton(
            onPressed: _isLoadingGps ? null : _handleGpsSelection,
            style: ElevatedButton.styleFrom(
              backgroundColor: ColorRes.anisGreen,
              foregroundColor: ColorRes.white,
              padding: EdgeInsets.symmetric(vertical: 14.h),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12.r),
              ),
              elevation: 0,
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                if (_isLoadingGps)
                  SizedBox(
                    width: 20.r,
                    height: 20.r,
                    child: const CircularProgressIndicator(
                      color: ColorRes.white,
                      strokeWidth: 2,
                    ),
                  )
                else
                  Icon(Icons.my_location_rounded, size: 20.r),
                const Sizer(width: 8),
                Text(
                  _isLoadingGps ? 'جاري تحديد موقعك...' : 'تحديد الموقع عبر الـ GPS',
                  style: tt.bodyMedium?.copyWith(
                    color: ColorRes.white,
                    fontWeight: FontWeight.w700,
                    fontFamily: 'Cairo',
                  ),
                ),
              ],
            ),
          ),
          const Sizer(height: 20),

          // Divider with text "أو اختر منطقة"
          Row(
            children: [
              const Expanded(child: Divider(color: ColorRes.anisLine)),
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 10.w),
                child: Text(
                  'أو اختر منطقة يدوياً',
                  style: tt.bodySmall?.copyWith(
                    color: ColorRes.anisTextMuted,
                    fontWeight: FontWeight.w600,
                    fontFamily: 'Cairo',
                  ),
                ),
              ),
              const Expanded(child: Divider(color: ColorRes.anisLine)),
            ],
          ),
          const Sizer(height: 16),

          // Regions Grid list
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: _regions.length,
            gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              mainAxisSpacing: 10.h,
              crossAxisSpacing: 10.w,
              childAspectRatio: 2.2,
            ),
            itemBuilder: (context, index) {
              final region = _regions[index];
              return InkWell(
                onTap: () => _handleRegionSelection(region),
                borderRadius: BorderRadius.circular(12.r),
                child: Container(
                  decoration: BoxDecoration(
                    color: ColorRes.anisCardBg,
                    border: Border.all(
                      color: ColorRes.anisLine,
                      width: 1,
                    ),
                    borderRadius: BorderRadius.circular(12.r),
                  ),
                  padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
                  alignment: Alignment.center,
                  child: Row(
                    children: [
                      Icon(
                        Icons.location_on_outlined,
                        color: ColorRes.anisGreen,
                        size: 18.r,
                      ),
                      const Sizer(width: 6),
                      Expanded(
                        child: Text(
                          region.name,
                          style: tt.bodySmall?.copyWith(
                            color: ColorRes.anisTextDark,
                            fontWeight: FontWeight.bold,
                            fontFamily: 'Cairo',
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}

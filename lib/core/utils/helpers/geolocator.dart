// import 'package:geocoding/geocoding.dart';
// import 'package:geolocator/geolocator.dart';
// import 'package:latlong2/latlong.dart';
// import '../../error/failure.dart';
//
//
// class GeolocatorService {
//   late Position pos;
//   GeolocatorService._();
//   static final GeolocatorService _instance = GeolocatorService._();
//   factory GeolocatorService() => _instance;
//   /// This method gets the current location of the device.
//   Future<LatLng> getCurrentLocation() async {
//     try {
//       pos = await Geolocator.getCurrentPosition(
//         desiredAccuracy: LocationAccuracy.bestForNavigation,
//       );
//       return LatLng(pos.latitude, pos.longitude);
//     } catch (e) {
//       throw ServerFailure(message: "no interNet Connection");
//     }
//   }
//
//   Future<String> getPlace ()async{
//     // final  placeDate = await placemarkFromCoordinates(pos.latitude, pos.longitude);
//     final  placeDate = await placemarkFromCoordinates(21.42664, 39.82563);
//     return placeDate[0].country ?? '';
//   }
//
//   /// This method enable location services on the device.
//   Future<bool> enableLocationServices() async {
//     bool serviceEnabled;
//     serviceEnabled = await Geolocator.isLocationServiceEnabled();
//     if (!serviceEnabled) {
//       await Geolocator.openLocationSettings();
//       serviceEnabled = await Geolocator.isLocationServiceEnabled();
//     }
//     serviceEnabled = await Geolocator.isLocationServiceEnabled();
//     return serviceEnabled;
//   }
//
//
//   // Future<LatLng?> getCurrentAfterCheckLocationServices() async {
//   //   Stream<ServiceStatus> serviceStatusStream =
//   //       Geolocator.getServiceStatusStream();
//   //   bool serviceEnabled;
//   //   serviceEnabled = await Geolocator.isLocationServiceEnabled();
//   //   if (serviceEnabled) {
//   //     getCurrentLocation();
//   //   } else {
//   //     await Geolocator.openLocationSettings();
//   //     await Future.delayed(const Duration(seconds: 2));
//   //     await serviceStatusStream.listen((ServiceStatus status) {
//   //       print("service status: $status");
//   //       print("service status: $status");
//   //       serviceEnabled = status == ServiceStatus.enabled;
//   //       if (serviceEnabled) {
//   //         getCurrentLocation();
//   //       }
//   //     });
//   //   }
//   // }
//
//   /// This method checks if location services are enabled on the device.
//   Future<bool> isLocationEnabled() async {return await Geolocator.isLocationServiceEnabled();}
//   static Stream<Position> getPositionStream() {
//     return Geolocator.getPositionStream(
//       locationSettings: LocationSettings(
//         accuracy: LocationAccuracy.bestForNavigation,
//         distanceFilter: 10, // Adjust as needed for real-time updates
//       ),);}}
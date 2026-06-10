import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';

class LocationHelper {
  /// Determines the current position of the device.
  /// Handles checking service enablement and requesting permissions.
  static Future<Position> determinePosition() async {
    bool serviceEnabled;
    LocationPermission permission;

    // Test if location services are enabled.
    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      throw const LocationServiceDisabledException();
    }

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        throw Exception('Location permissions are denied');
      }
    }

    if (permission == LocationPermission.deniedForever) {
      throw Exception(
        'Location permissions are permanently denied, we cannot request permissions.',
      );
    }

    // Fetch coordinates
    return await Geolocator.getCurrentPosition(
      locationSettings: const LocationSettings(
        accuracy: LocationAccuracy.high,
        timeLimit: Duration(seconds: 10),
      ),
    );
  }

  /// Reverse geocodes the given lat/lng coordinates to a human-readable city or region name.
  static Future<String> getAddressFromLatLng(
    double latitude,
    double longitude,
  ) async {
    try {
      final placemarks = await placemarkFromCoordinates(
        latitude,
        longitude,
        localeIdentifier: 'ar', // default to Arabic for local audience
      );

      if (placemarks.isNotEmpty) {
        final place = placemarks.first;
        
        // Try to construct a readable location string
        final district = place.subLocality ?? place.locality;
        final city = place.administrativeArea ?? place.country;

        if (district != null && district.isNotEmpty && city != null && city.isNotEmpty) {
          return '$district، $city';
        } else if (city != null && city.isNotEmpty) {
          return city;
        } else if (place.name != null) {
          return place.name!;
        }
      }
      return 'موقع غير معروف';
    } catch (_) {
      // Fallback in case of geocoding issues/no network
      return 'الموقع الحالي';
    }
  }
}

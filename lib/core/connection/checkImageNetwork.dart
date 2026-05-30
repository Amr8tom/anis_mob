import 'package:http/http.dart' as http;

class checkImageNetwork {
  // String NetworkImageURL;
  // checkImageNetwork({required this.NetworkImageURL});

  static Future<bool> checkImageStatus(
      {required String NetworkImageURL}) async {
    final response = await http.head(Uri.parse(NetworkImageURL));
    return response.statusCode == 200 ? true : false;
  }
}

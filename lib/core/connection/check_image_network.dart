import 'package:http/http.dart' as http;

class CheckImageNetwork {
  static Future<bool> checkImageStatus({required String networkImageUrl}) async {
    final response = await http.head(Uri.parse(networkImageUrl));
    return response.statusCode == 200;
  }
}

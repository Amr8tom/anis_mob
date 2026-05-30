import 'dart:convert';
import 'dart:io';

File base64ToFileByAmrAlaa({
  required String base64String,
  String? fileName,
})  {
  /// Decode the base64 string
  final bytes = base64Decode(base64String);
  /// Create a file in the temporary directory
  final directory = Directory.systemTemp;
  final file = File('${directory.path}/$fileName');
  /// Write the bytes to the file
   file.writeAsBytes(bytes);
  /// Return the file
  return file;
}

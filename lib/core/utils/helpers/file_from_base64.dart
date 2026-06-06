import 'dart:convert';
import 'dart:io';
import 'package:path_provider/path_provider.dart';
import 'package:share_plus/share_plus.dart';

Future<void> downloadBase64File({
  required String base64String,
  required String fileName,
}) async {
  try {
    // 1. Clean the Base64 string if it contains a data URI scheme
    // (e.g., "data:application/pdf;base64,JVBER...")
    final cleanBase64 = base64String.contains(',')
        ? base64String.split(',').last
        : base64String;

    // 2. Decode the Base64 string into bytes
    final bytes = base64Decode(cleanBase64);

    // 3. Get a safe directory to temporarily store the file
    final dir = await getTemporaryDirectory();

    // 4. Create the file path
    final file = File('${dir.path}/$fileName');

    // 5. Write the bytes to the file
    await file.writeAsBytes(bytes);

    // 6. Trigger the native save/share sheet
    // This allows the user to tap "Save to Files" on iOS or Android
    await SharePlus.instance.share(
      ShareParams(
        files: [XFile(file.path)],
        text: 'Save your file',
      ),
    );
  } catch (_) {}
}

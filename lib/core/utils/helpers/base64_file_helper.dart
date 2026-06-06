import 'dart:convert';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:path_provider/path_provider.dart';
import 'package:share_plus/share_plus.dart';

import '../../constants/colors.dart';

/// Outcome of [Base64FileHelper.downloadAndShare].
enum DownloadStatus {
  /// File saved AND share sheet opened successfully.
  shared,

  /// File saved to disk but the share plugin wasn't available.
  /// Usually means the app needs a full rebuild after adding `share_plus`.
  savedOnly,

  /// Couldn't decode the base64 or couldn't write to disk.
  failure,
}

/// Result wrapper returned by [Base64FileHelper.downloadAndShare].
class DownloadResult {
  final DownloadStatus status;
  final String? filePath;

  const DownloadResult({required this.status, this.filePath});
}

/// Lightweight metadata describing a base64-encoded attachment.
///
/// Used by the attachment card UI to render the right icon / color / label
/// without forcing the API to send a separate `mimeType` field.
class Base64FileInfo {
  final String mimeType;

  /// File extension WITHOUT the leading dot (e.g. `pdf`, `xlsx`, `png`).
  final String extension;

  /// Human-readable label (e.g. `PDF Document`, `Excel Spreadsheet`).
  final String label;

  /// Material icon used in the attachment card.
  final IconData icon;

  /// Accent color used to tint the icon container.
  final Color color;

  /// Decoded binary length in bytes — handy for showing file size.
  final int sizeInBytes;

  const Base64FileInfo({
    required this.mimeType,
    required this.extension,
    required this.label,
    required this.icon,
    required this.color,
    required this.sizeInBytes,
  });
}

/// Helper utilities for working with arbitrary base64 attachments.
///
/// The API may return:
///   - a raw base64 string (no prefix), OR
///   - a data-URI like `data:application/pdf;base64,JVBERi0xLjcK...`
///
/// Both forms are handled; when no MIME type is provided, the file's
/// "magic bytes" (signature) are inspected to infer the type.
class Base64FileHelper {
  Base64FileHelper._();

  /// Detects the type of the file encoded in [base64String].
  ///
  /// Returns `null` if the string can't be decoded.
  static Base64FileInfo? detect(String base64String) {
    if (base64String.isEmpty) return null;

    String? mimeFromUri;
    String cleaned = base64String;

    /// Pull MIME hint from the data-URI prefix when present.
    if (base64String.startsWith('data:')) {
      final commaIdx = base64String.indexOf(',');
      if (commaIdx != -1) {
        final header =
            base64String.substring(5, commaIdx); // e.g. "image/png;base64"
        mimeFromUri = header.split(';').first.trim();
        cleaned = base64String.substring(commaIdx + 1);
      }
    }

    /// Strip whitespace / line breaks that sometimes come back from APIs.
    cleaned = cleaned.replaceAll(RegExp(r'\s'), '');

    Uint8List bytes;
    try {
      bytes = base64Decode(cleaned);
    } catch (_) {
      return null;
    }

    final mime = mimeFromUri ?? _sniffMime(bytes);
    return _infoFor(mime, bytes.length);
  }

  /// Decodes [base64String], writes it to a file on disk with the correct
  /// extension, then attempts to trigger the native share/save sheet via
  /// `share_plus` so the user can "Save to Files" / "Open in" / "Share".
  ///
  /// The file is ALWAYS written to disk (even if the share sheet fails to
  /// open), so the result tells the caller exactly which state we ended up
  /// in:
  ///   - [DownloadStatus.shared]   — file written + share sheet opened
  ///   - [DownloadStatus.savedOnly] — file written but share plugin missing
  ///                                 (e.g. app needs full rebuild after
  ///                                 adding `share_plus`)
  ///   - [DownloadStatus.failure]  — base64 invalid or disk write failed
  static Future<DownloadResult> downloadAndShare({
    required String base64String,
    String? customFileName,
  }) async {
    final info = detect(base64String);
    if (info == null) {
      return const DownloadResult(status: DownloadStatus.failure);
    }

    String filePath;
    try {
      /// Re-decode the cleaned bytes (detect() already cleaned for us
      /// but kept the result private — re-clean to keep this helper
      /// self-contained).
      final cleaned = base64String.startsWith('data:')
          ? base64String.substring(base64String.indexOf(',') + 1)
          : base64String;
      final bytes = base64Decode(cleaned.replaceAll(RegExp(r'\s'), ''));

      /// Save to the app documents directory so the file survives long
      /// enough for the user to act on it (temp dir gets cleared more
      /// aggressively by the OS).
      final dir = await getApplicationDocumentsDirectory();
      final fileName = customFileName != null
          ? '$customFileName.${info.extension}'
          : 'attachment_${DateTime.now().millisecondsSinceEpoch}.${info.extension}';

      final file = File('${dir.path}/$fileName');
      await file.writeAsBytes(bytes);
      filePath = file.path;
    } catch (_) {
      return const DownloadResult(status: DownloadStatus.failure);
    }

    /// Try the native share sheet — if `share_plus` isn't linked into the
    /// running build (common right after adding the dependency), surface
    /// a "savedOnly" result instead of crashing.
    try {
      await SharePlus.instance.share(
        ShareParams(
          files: [XFile(filePath)],
          text: filePath.split('/').last,
        ),
      );
      return DownloadResult(
        status: DownloadStatus.shared,
        filePath: filePath,
      );
    } on MissingPluginException {
      return DownloadResult(
        status: DownloadStatus.savedOnly,
        filePath: filePath,
      );
    } catch (_) {
      return DownloadResult(
        status: DownloadStatus.savedOnly,
        filePath: filePath,
      );
    }
  }

  /// Pretty-prints a byte count: `2.3 MB`, `145 KB`, `42 B`.
  static String formatSize(int bytes) {
    if (bytes < 1024) return '$bytes B';
    if (bytes < 1024 * 1024) {
      return '${(bytes / 1024).toStringAsFixed(1)} KB';
    }
    if (bytes < 1024 * 1024 * 1024) {
      return '${(bytes / (1024 * 1024)).toStringAsFixed(1)} MB';
    }
    return '${(bytes / (1024 * 1024 * 1024)).toStringAsFixed(2)} GB';
  }

  // ---------------------------------------------------------------------
  // Private — MIME sniffing from "magic bytes" (file signatures)
  // ---------------------------------------------------------------------

  static String _sniffMime(Uint8List b) {
    if (b.length < 4) return 'application/octet-stream';

    /// PDF — "%PDF"
    if (b[0] == 0x25 && b[1] == 0x50 && b[2] == 0x44 && b[3] == 0x46) {
      return 'application/pdf';
    }

    /// PNG
    if (b[0] == 0x89 && b[1] == 0x50 && b[2] == 0x4E && b[3] == 0x47) {
      return 'image/png';
    }

    /// JPEG
    if (b[0] == 0xFF && b[1] == 0xD8 && b[2] == 0xFF) {
      return 'image/jpeg';
    }

    /// GIF
    if (b[0] == 0x47 && b[1] == 0x49 && b[2] == 0x46 && b[3] == 0x38) {
      return 'image/gif';
    }

    /// BMP
    if (b[0] == 0x42 && b[1] == 0x4D) {
      return 'image/bmp';
    }

    /// WebP — RIFF....WEBP
    if (b.length >= 12 &&
        b[0] == 0x52 &&
        b[1] == 0x49 &&
        b[2] == 0x46 &&
        b[3] == 0x46 &&
        b[8] == 0x57 &&
        b[9] == 0x45 &&
        b[10] == 0x42 &&
        b[11] == 0x50) {
      return 'image/webp';
    }

    /// ZIP-based Office (docx/xlsx/pptx) — PK\x03\x04
    if (b[0] == 0x50 && b[1] == 0x4B && b[2] == 0x03 && b[3] == 0x04) {
      /// We can't cheaply distinguish docx vs xlsx vs pptx without
      /// parsing the ZIP central directory, so we mark it as a generic
      /// modern Office document. The data-URI path above usually catches
      /// the exact subtype anyway.
      return 'application/zip';
    }

    /// Legacy Office (doc/xls/ppt) — D0 CF 11 E0 A1 B1 1A E1
    if (b.length >= 8 &&
        b[0] == 0xD0 &&
        b[1] == 0xCF &&
        b[2] == 0x11 &&
        b[3] == 0xE0 &&
        b[4] == 0xA1 &&
        b[5] == 0xB1 &&
        b[6] == 0x1A &&
        b[7] == 0xE1) {
      return 'application/vnd.ms-office';
    }

    /// Plain text — heuristic: all printable ASCII in the first 64 bytes.
    final sample = b.length > 64 ? b.sublist(0, 64) : b;
    final isPrintable = sample.every(
      (c) => c == 0x09 || c == 0x0A || c == 0x0D || (c >= 0x20 && c <= 0x7E),
    );
    if (isPrintable) return 'text/plain';

    return 'application/octet-stream';
  }

  static Base64FileInfo _infoFor(String mime, int size) {
    switch (mime) {
      case 'application/pdf':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'pdf',
          label: 'PDF Document',
          icon: Icons.picture_as_pdf_rounded,
          color: ColorRes.error,
          sizeInBytes: size,
        );
      case 'image/png':
      case 'image/jpeg':
      case 'image/gif':
      case 'image/bmp':
      case 'image/webp':
        return Base64FileInfo(
          mimeType: mime,
          extension:
              mime.split('/').last == 'jpeg' ? 'jpg' : mime.split('/').last,
          label: 'Image',
          icon: Icons.image_rounded,
          color: ColorRes.primary,
          sizeInBytes: size,
        );
      case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'docx',
          label: 'Word Document',
          icon: Icons.description_rounded,
          color: ColorRes.info,
          sizeInBytes: size,
        );
      case 'application/msword':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'doc',
          label: 'Word Document',
          icon: Icons.description_rounded,
          color: ColorRes.info,
          sizeInBytes: size,
        );
      case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'xlsx',
          label: 'Excel Spreadsheet',
          icon: Icons.table_chart_rounded,
          color: ColorRes.success,
          sizeInBytes: size,
        );
      case 'application/vnd.ms-excel':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'xls',
          label: 'Excel Spreadsheet',
          icon: Icons.table_chart_rounded,
          color: ColorRes.success,
          sizeInBytes: size,
        );
      case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'pptx',
          label: 'PowerPoint',
          icon: Icons.slideshow_rounded,
          color: ColorRes.warning,
          sizeInBytes: size,
        );
      case 'application/zip':
      case 'application/vnd.ms-office':

        /// Catch-all for ZIP-/OLE-based files when no MIME prefix was sent.
        return Base64FileInfo(
          mimeType: mime,
          extension: mime == 'application/zip' ? 'zip' : 'doc',
          label: 'Document',
          icon: Icons.folder_zip_rounded,
          color: ColorRes.warning,
          sizeInBytes: size,
        );
      case 'text/plain':
        return Base64FileInfo(
          mimeType: mime,
          extension: 'txt',
          label: 'Text File',
          icon: Icons.article_rounded,
          color: ColorRes.grey2,
          sizeInBytes: size,
        );
      default:
        return Base64FileInfo(
          mimeType: mime,
          extension: 'bin',
          label: 'File',
          icon: Icons.insert_drive_file_rounded,
          color: ColorRes.grey2,
          sizeInBytes: size,
        );
    }
  }
}

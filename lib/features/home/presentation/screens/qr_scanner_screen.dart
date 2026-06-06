import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import 'package:anis/common/widgets/sizeboxs/sizer.dart';
import 'package:anis/core/constants/app_sizes.dart';
import 'package:anis/core/constants/colors.dart';
import 'package:anis/generated/l10n.dart';

import '../controller/home_cubit.dart';

/// Camera-based QR scanner screen.
/// On successful decode, calls [HomeCubit.checkIn] and pops with [true].
/// On failure or cancel, pops with [false].
class QrScannerScreen extends StatefulWidget {
  const QrScannerScreen({super.key});

  @override
  State<QrScannerScreen> createState() => _QrScannerScreenState();
}

class _QrScannerScreenState extends State<QrScannerScreen> {
  final MobileScannerController _controller = MobileScannerController();
  bool _scanned = false;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  Future<void> _onDetect(BarcodeCapture capture) async {
    if (_scanned) return;
    final barcode = capture.barcodes.firstOrNull;
    if (barcode == null || barcode.rawValue == null) return;

    _scanned = true;
    await _controller.stop();

    if (!mounted) return;
    final cubit = context.read<HomeCubit>();
    await cubit.checkIn(barcode.rawValue!);

    if (!mounted) return;
    final success = context.read<HomeCubit>().state.isCheckedIn;
    Navigator.of(context).pop(success);
  }

  @override
  Widget build(BuildContext context) {
    final tt = Theme.of(context).textTheme;

    return BlocListener<HomeCubit, HomeState>(
      listenWhen: (p, c) => p.attendanceError != c.attendanceError,
      listener: (_, state) {
        if (state.attendanceError != null) {
          setState(() => _scanned = false);
          _controller.start();
        }
      },
      child: Scaffold(
        backgroundColor: ColorRes.anisNavy,
        body: Stack(
          children: [
            // Camera view
            MobileScanner(
              controller: _controller,
              onDetect: _onDetect,
            ),

            // Scan frame overlay
            const _ScanFrameOverlay(),

            // Top bar
            SafeArea(
              child: Padding(
                padding: EdgeInsets.all(AppSizes.padding),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    // Close
                    GestureDetector(
                      onTap: () => Navigator.of(context).pop(false),
                      child: Container(
                        padding: EdgeInsets.all(AppSizes.sm),
                        decoration: BoxDecoration(
                          color: ColorRes.white.withValues(alpha: 0.15),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.close_rounded,
                          color: ColorRes.white,
                          size: 22,
                        ),
                      ),
                    ),
                    // Torch toggle
                    GestureDetector(
                      onTap: _controller.toggleTorch,
                      child: Container(
                        padding: EdgeInsets.all(AppSizes.sm),
                        decoration: BoxDecoration(
                          color: ColorRes.white.withValues(alpha: 0.15),
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.flashlight_on_rounded,
                          color: ColorRes.white,
                          size: 22,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Bottom instruction label
            Positioned(
              bottom: 80,
              left: AppSizes.xl,
              right: AppSizes.xl,
              child: BlocBuilder<HomeCubit, HomeState>(
                buildWhen: (p, c) =>
                    p.attendanceStatus != c.attendanceStatus,
                builder: (_, state) {
                  final isLoading = state.attendanceStatus.isCheckingIn;
                  return Column(
                    children: [
                      if (isLoading)
                        const CircularProgressIndicator(
                          color: ColorRes.anisGreen,
                          strokeWidth: 3,
                        )
                      else
                        const Icon(
                          Icons.qr_code_scanner_rounded,
                          color: ColorRes.white,
                          size: 36,
                        ),
                      const Sizer(height: 12),
                      Text(
                        isLoading
                            ? S.current.checkingIn
                            : S.current.scanQrToCheckIn,
                        textAlign: TextAlign.center,
                        style: tt.bodyMedium?.copyWith(
                          color: ColorRes.white,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// Scan frame decorative overlay

class _ScanFrameOverlay extends StatelessWidget {
  const _ScanFrameOverlay();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: SizedBox(
        width: 240,
        height: 240,
        child: CustomPaint(painter: _FramePainter()),
      ),
    );
  }
}

class _FramePainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    const cornerLen = 28.0;
    const thick     = 4.0;
    const r         = 10.0;

    final paint = Paint()
      ..color = ColorRes.anisGreen
      ..strokeWidth = thick
      ..strokeCap = StrokeCap.round
      ..style = PaintingStyle.stroke;

    final w = size.width;
    final h = size.height;

    // Corners: top-left, top-right, bottom-right, bottom-left
    final corners = [
      (Offset(0, r),         Offset(0, cornerLen),      Offset(r, 0),          Offset(cornerLen, 0)),
      (Offset(w - r, 0),     Offset(w - cornerLen, 0),  Offset(w, r),          Offset(w, cornerLen)),
      (Offset(w, h - r),     Offset(w, h - cornerLen),  Offset(w - r, h),      Offset(w - cornerLen, h)),
      (Offset(0, h - r),     Offset(0, h - cornerLen),  Offset(r, h),          Offset(cornerLen, h)),
    ];

    for (final (a1, a2, b1, b2) in corners) {
      canvas.drawLine(a1, a2, paint);
      canvas.drawLine(b1, b2, paint);
    }
  }

  @override
  bool shouldRepaint(_FramePainter old) => false;
}

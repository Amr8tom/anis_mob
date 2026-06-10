import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';

class ScanFrameOverlay extends StatelessWidget {
  const ScanFrameOverlay({super.key});

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

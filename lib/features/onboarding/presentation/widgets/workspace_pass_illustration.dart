import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';

class WorkspacePassIllustration extends StatelessWidget {
  const WorkspacePassIllustration({super.key});

  @override
  Widget build(BuildContext context) =>
      CustomPaint(size: const Size(double.infinity, double.infinity), painter: _WorkspacePassPainter());
}

class _WorkspacePassPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final cx = size.width / 2;
    final cy = size.height / 2;

    // Background glow
    canvas.drawCircle(
      Offset(cx, cy - 10),
      size.width * 0.44,
      Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.09)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 60),
    );

    // ── Building body ──
    final bldW = size.width * 0.46;
    final bldH = size.height * 0.50;
    final bldLeft = cx - bldW / 2;
    final bldTop  = cy - bldH / 2 - 10;

    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, bldH),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.white
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 16),
    );
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, bldH),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.anisCardBg,
    );

    // ── Green roof strip ──
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(bldLeft, bldTop, bldW, 10),
        const Radius.circular(14),
      ),
      Paint()..color = ColorRes.anisGreen,
    );

    // ── Windows grid ──
    final winPaint = Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.22);
    final winActivePaint = Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.80);
    const cols = 3;
    const rows = 3;
    final winW = bldW * 0.18;
    final winH = bldH * 0.12;
    final hGap = (bldW - cols * winW) / (cols + 1);
    final vGap = (bldH * 0.75 - rows * winH) / (rows + 1);
    final gridTop = bldTop + bldH * 0.18;

    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        final x = bldLeft + hGap + c * (winW + hGap);
        final y = gridTop + vGap + r * (winH + vGap);
        final isActive = (r == 0 && c == 1) || (r == 1 && c == 0) || (r == 2 && c == 2);
        canvas.drawRRect(
          RRect.fromRectAndRadius(Rect.fromLTWH(x, y, winW, winH), const Radius.circular(3)),
          isActive ? winActivePaint : winPaint,
        );
      }
    }

    // ── Door ──
    final doorW = bldW * 0.20;
    final doorH = bldH * 0.20;
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(cx - doorW / 2, bldTop + bldH - doorH, doorW, doorH),
        const Radius.circular(4),
      ),
      Paint()..color = ColorRes.anisGreen,
    );

    // ── Subscription badge ──
    final badgeCx = bldLeft + bldW + 8;
    final badgeCy = bldTop + 28.0;
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromCenter(center: Offset(badgeCx, badgeCy), width: 68, height: 30),
        const Radius.circular(15),
      ),
      Paint()..color = ColorRes.anisGold,
    );
    _drawText(canvas, '✓ Pass', Offset(badgeCx, badgeCy),
        const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w800));

    // ── Location pin ──
    final pin = Paint()..color = ColorRes.anisGreen;
    canvas.drawCircle(Offset(cx, bldTop - 22), 11, pin);
    canvas.drawCircle(Offset(cx, bldTop - 22), 5,
        Paint()..color = ColorRes.white);
    final path = Path()
      ..moveTo(cx - 11, bldTop - 22)
      ..lineTo(cx + 11, bldTop - 22)
      ..lineTo(cx, bldTop - 5);
    canvas.drawPath(path, pin);
  }

  void _drawText(Canvas canvas, String text, Offset centre, TextStyle style) {
    final tp = TextPainter(
      text: TextSpan(text: text, style: style),
      textDirection: TextDirection.ltr,
    )..layout();
    tp.paint(canvas, centre - Offset(tp.width / 2, tp.height / 2));
  }

  @override
  bool shouldRepaint(_WorkspacePassPainter old) => false;
}

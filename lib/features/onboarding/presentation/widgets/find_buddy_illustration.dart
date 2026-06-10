import 'package:flutter/material.dart';
import 'package:anis/core/constants/colors.dart';

class FindBuddyIllustration extends StatelessWidget {
  const FindBuddyIllustration({super.key});

  @override
  Widget build(BuildContext context) =>
      CustomPaint(size: const Size(double.infinity, double.infinity), painter: _FindBuddyPainter());
}

class _FindBuddyPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final cx = size.width / 2;
    final cy = size.height / 2;

    // Background glow
    canvas.drawCircle(
      Offset(cx, cy),
      size.width * 0.45,
      Paint()..color = ColorRes.anisGold.withValues(alpha: 0.08)
             ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 65),
    );

    // Avatar positions
    final centre = Offset(cx, cy);
    final top    = Offset(cx, cy - size.height * 0.26);
    final left   = Offset(cx - size.width * 0.30, cy + size.height * 0.10);
    final right  = Offset(cx + size.width * 0.30, cy + size.height * 0.10);

    // Connection lines
    final linePaint = Paint()
      ..color = ColorRes.anisGreen.withValues(alpha: 0.22)
      ..strokeWidth = 2
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    for (final end in [top, left, right]) {
      canvas.drawLine(centre, end, linePaint);
    }

    // Avatars
    final avatars = [
      (centre, ColorRes.anisGreen,       '📚', 36.0, true),
      (top,    ColorRes.anisGold,        '🎯', 26.0, false),
      (left,   ColorRes.anisTagBlueTxt,  '👩‍🎓', 26.0, false),
      (right,  ColorRes.anisTagPinkTxt,  '👨‍💻', 26.0, false),
    ];

    for (final (pos, color, emoji, r, isMain) in avatars) {
      // Shadow
      canvas.drawCircle(pos, r + 4,
          Paint()..color = color.withValues(alpha: 0.15)
                 ..maskFilter = const MaskFilter.blur(BlurStyle.normal, 10));
      // White fill
      canvas.drawCircle(pos, r, Paint()..color = ColorRes.white);
      // Ring
      canvas.drawCircle(pos, r,
          Paint()..color = isMain ? ColorRes.anisGreen.withValues(alpha: 0.80) : color.withValues(alpha: 0.30)
                 ..style = PaintingStyle.stroke
                 ..strokeWidth = isMain ? 2.5 : 1.5);
      // Emoji
      final tp = TextPainter(
        text: TextSpan(text: emoji, style: TextStyle(fontSize: r * 0.90)),
        textDirection: TextDirection.ltr,
      )..layout();
      tp.paint(canvas, pos - Offset(tp.width / 2, tp.height / 2));
    }

    // Pulse ring on centre
    canvas.drawCircle(centre, 46,
        Paint()..color = ColorRes.anisGreen.withValues(alpha: 0.12)
               ..style = PaintingStyle.stroke
               ..strokeWidth = 10);

    // ✓ Match badge
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromCenter(
          center: Offset(cx + size.width * 0.26, cy - size.height * 0.24),
          width: 68, height: 26,
        ),
        const Radius.circular(13),
      ),
      Paint()..color = ColorRes.anisGreen,
    );
    final tp2 = TextPainter(
      text: const TextSpan(
        text: '✓  Match',
        style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700),
      ),
      textDirection: TextDirection.ltr,
    )..layout();
    tp2.paint(canvas,
        Offset(cx + size.width * 0.26 - tp2.width / 2,
               cy - size.height * 0.24 - tp2.height / 2));
  }

  @override
  bool shouldRepaint(_FindBuddyPainter old) => false;
}
